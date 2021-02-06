<?php

namespace App\Controller\Shop;

use App\Entity\Shop\Commandes;
use App\Entity\Shop\Products;
use App\Entity\Status;
use App\Entity\User;
use App\Event\CommandeEvent;
use App\Interfaces\NotificationsInterface;
use App\PDF\PdfServices;
use App\Repository\Shop\AdressesRepository;
use App\Services\Carts\CartsServices;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop/commandes", schemes={"https"})
 */
class CommandesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var AdressesRepository
     */
    private $adressesRepository;
    /**
     * @var CartsServices
     */
    private $cartsServices;
    /**
     * @var NotificationsInterface
     */
    private $notifications;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * CommandesController constructor.
     * @param FlashyNotifier $flashy
     * @param SessionInterface $session
     * @param AdressesRepository $adressesRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param NotificationsInterface $notifications
     * @param CartsServices $cartsServices
     */
    public function __construct(
        FlashyNotifier $flashy,
        SessionInterface $session,
        AdressesRepository $adressesRepository,
        EventDispatcherInterface $eventDispatcher,
        NotificationsInterface $notifications,
        CartsServices $cartsServices
    )
    {
        $this->flashy = $flashy;
        $this->session = $session;
        $this->adressesRepository = $adressesRepository;
        $this->cartsServices = $cartsServices;
        $this->notifications = $notifications;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @Route("/checkout", name="shop.commandes.checkout", methods={"GET|POST"})
     * @param Request $request
     * @param ContainerInterface $container
     * @return Response
     */
    public function checkout(Request $request, ContainerInterface $container)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mode = $this->session->get('mode', []);
        $point = $this->session->get('pointretrait', []);
        $paniers = $this->cartsServices->getFullCart();
        if(!$mode){
            $this->flashy->info('Veuillez sélectionner un mode de livraison en cliquant sur "Procédez à l\'achat"');
            $this->addFlash('info','Veuillez sélectionner un mode de livraison en cliquant sur "Procédez à l\'achat"');
            return $this->redirectToRoute('cart.index');
        }

        if(!$paniers){
            $this->flashy->info('Veuillez ajouter un produit à votre panier');
            $this->addFlash('warning','Veuillez ajouter un produit à votre panier');
            return $this->redirectToRoute('cart.index');
        }

        if($request->getMethod() === 'POST'){
            $em = $this->getDoctrine()->getManager();
            $tva = $this->cartsServices->getTotalTva();
            $livraison = $this->session->get('livraison');
            $facturation = $this->session->get('facturation');
            $note = $request->request->get('note') ?? null;

            $chiffreEnLettre = $container->get('chiffre_en_lettre');

            $commande = new Commandes();
            $products = [];
            $totalHT = 0;

            foreach ($paniers as $panier){
                $totalHT += $panier['product']->getPrice() * $panier['qte'];
                $products['products'][$panier['product']->getId()] = [
                    'quantity' => $panier['qte'],
                    'product' => $panier['product'],
                    'total' => round($panier['product']->getPrice() * $panier['qte'], 0)
                ];
            }

            //Preparation de la commande
            $lastCommandeRef = $this->getDoctrine()->getRepository(Commandes::class)->getLastCommande();

            if($lastCommandeRef['reference'] === null){
                $ref = 0;
            }else{
                $ref = $lastCommandeRef['reference'];
            }

            //On met à jour les quantités des produits commandés
            foreach ($products as $product){
                foreach ($product as $item){
                    $getProductParents = $this->getDoctrine()->getRepository(Products::class)->find($item['product']->getId());

                    if ($getProductParents->getQuantity() < $item['quantity']){
                        $this->flashy->error("Quantité du produit ".$getProductParents->getName()." insufisante.");
                        return $this->redirectToRoute('cart.index');
                    }
                    $getProductParents->setQuantity($getProductParents->getQuantity() -  $item['quantity']);
                    $getProductParents->setPayementAt(new \DateTime('now'));
                    $em->flush();
                }
            }
            //Ajoute les adresses au tableau de produits
            $products['adresses'] = [
                'livraison' => $livraison,
                'facturation' => $facturation,
            ];

            /**
             * @var $status Status
             */
            $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['id' => 1]);
            /**
             * @var $user User
             */
            $user = $this->getUser();

            $totalTTC = $tva + $totalHT;
            $montantlettre = strtoupper($chiffreEnLettre->Conversion($totalTTC));
            $commande->setReference($ref + 1);
            $commande->setProducts($paniers);
            if ($products['adresses']){
                $commande->setAdresses($products['adresses']);
            }
            if ($mode){
                $commande->setModelivraison($mode[0]['mode']);
            }

            if ($point){
                $commande->setPointrelais($point);
            }

            $commande->setModepaiment(null);
            $commande->setValider(false);
            $commande->setTotalht($totalHT);
            $commande->setTotaltva($tva);
            $commande->setTotalttc($totalTTC);
            $commande->setChiffreenlettre($montantlettre);
            $commande->setUser($user);
            $commande->setNote($note);
            $commande->setNotification(0);
            $commande->setStatus($status);

            $em->persist($commande);
            $em->flush();

            //Envoi de la notification
            $this->notifications->save(
                'Nouvelle commande de: '.$this->getUser()->getUsername(),
                'Nouvelle commande',
                $commande,
                'check',
                'green'
            );

            $products = [];
            foreach ($commande->getProducts() as $product){
                $products[] = [
                    'product' => $this->getDoctrine()->getRepository(Products::class)->find($product['product']->getId()),
                    'quantity' => $product['qte']
                ];
            }

            //Envois du mail a aiwatch et au client (2mails au total)
            $event = new CommandeEvent($commande);
            $this->eventDispatcher->dispatch($event);

            //On vide le panier
            $this->cleanSession();
            $this->flashy->success("Votre commande a été prise en compte.");
            $this->addFlash("success","Votre commande a été prise en compte.");
            return $this->redirectToRoute('cart.index');
        }

        return $this->redirectToRoute('cart.index');
    }

    /**
     * @Route("/generate/pdf/{id}", name="shop.commandes.generatepdf", methods={"GET"})
     * @param Commandes $commandes
     * @param PdfServices $pdfServices
     * @return Response
     */
    public function generate_pdf(Commandes $commandes, PdfServices $pdfServices): Response{
        /**
         * @var $commande Commandes
         */
        $commande = $this->getDoctrine()->getRepository(Commandes::class)->findOneBy([
            'valider' => true,
            'id' => $commandes->getId()
        ]);
        if(!$commande && $this->isGranted('ROLE_SUPER_ADMIN') || $this->isGranted('ROLE_ADMIN')){
            $this->flashy->error("Commande non validée.");
            $this->addFlash("danger","La commande doit être validée avec d'imprimer la facture.");
            return $this->redirectToRoute('admin.commandes.invoice', ['id' => $commandes->getId()]);
        }
        $products = [];
        foreach ($commande->getProducts() as $product){
            $products[] = [
                'product' => $this->getDoctrine()->getRepository(Products::class)->find($product['product']->getId()),
                'quantity' => $product['qte']
            ];
        }

        $title = 'Commande de '.$this->getUser()->getUsername();
        $pdfServices->generate($commande, $title, 'A4', 'pdf/default.html.twig', [
            'title' => $title,
            'commande' => $commande,
            'products' => $products
        ]);
    }

    private function cleanSession(){
        $this->cartsServices->clearCart();
        $this->session->remove('livraison');
        $this->session->remove('facturation');
        $this->session->remove('note');
        $this->session->remove('mode');
        $this->session->remove('pointretrait');
        $this->session->remove('modelivraison');
    }
}