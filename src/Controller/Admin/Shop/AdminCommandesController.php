<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Commandes;
use App\Form\Shop\CommandesType;
use App\Repository\Shop\CommandesRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products/commandes", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminCommandesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var PaginatorInterface
     */
    private $pagination;


    /**
     * AdminCommandesController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination)
    {
        $this->flashy = $flashy;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.commandes.index", methods={"GET"})
     * @param Request $request
     * @param CommandesRepository $commandesRepository
     * @return Response
     */
    public function index(Request $request, CommandesRepository $commandesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $commandes = $categories = $this->pagination->paginate(
            $commandesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/shops/commandes/index.html.twig', [
            'commandes' => $commandes,
            'title' => 'Liste des commandes',
            'current_page' => 'commandes',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/clients", name="admin.commandes.clients")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function clients(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $users = $userRepository->getClients('ROLE_USER');
        return $this->render('admin/shops/commandes/clients.html.twig', [
            'users' => $users,
            'title' => 'Liste des clients',
            'current_page' => 'clients',
            'current_global' => 'products'
        ]);
    }



    /**
     * @Route("/notifications", name="admin.commandes.notifs", methods={"GET"})
     * @param CommandesRepository $commandesRepository
     * @return Response
     */
    public function allnotifs(CommandesRepository $commandesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/shops/commandes/index.html.twig', [
            'commandes' => $commandesRepository->findBy(['notification' => 0], ['id' => 'DESC']),
            'title' => 'Liste des notifications',
            'current_page' => 'commandes',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.commandes.show", methods={"GET"})
     * @param Commandes $commande
     * @return Response
     */
    public function show(Commandes $commande): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/shops/commandes/show.html.twig', [
            'commande' => $commande,
            'title' => 'Détails commande N° '.$commande->getReference(),
            'libelle_liste' => 'Liste des commandes',
            'current_page' => 'commandes',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/invoice/{id}", name="admin.commandes.invoice", methods={"GET"})
     * @param Commandes $commande
     * @return Response
     */
    public function invoice(Commandes $commande): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('admin/shops/commandes/invoice.html.twig', [
            'commande' => $commande,
            'title' => 'Invoice commande N° '.$commande->getReference(),
            'libelle_liste' => 'Liste des commandes',
            'current_page' => 'commandes',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.commandes.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Commandes $commande
     * @return Response
     */
    public function edit(Request $request, Commandes $commande): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $manager = $this->getDoctrine()->getManager();
            $status = intval($form->get('status')->getData()->getId());

            /** Annuler de la commande */
            if($status === 3){//On annule la commande puis on envoi un email au client pour l'informer
                $motif = "<strong>Motif de l'annulation: </strong>".$commande->getMotifs() ?? '';
                $body = "Bonjour M. <strong>{$commande->getUser()->getNom()} {$commande->getUser()->getPrenoms()}</strong>,<br>
                Désolé, votre commande a été annulé par l'équipe de spiral<br>
                Votre N° de commande est: <strong>{$commande->getReference()}</strong> <br>
                {$motif}
                ";

            }
            /** Commande prete */
            if($status === 4){//La commande est prete puis on envoi un email au client pour l'informer
                $body = "Bonjour M. <strong>{$commande->getUser()->getNom()} {$commande->getUser()->getPrenoms()}</strong>,<br>
                Félicitation, votre commande est prête, vous pouvez dès à présent passer au magasin pour la récupérer<br>
                Votre N° de commande est: <strong>{$commande->getReference()}</strong>
                ";

            }
            /** Valider commande */
            if($status === 2){//On valide la commande puis on envoi un email au client pour l'informer
                $commande->setValider(true);
                $body = "Bonjour M. <strong>{$commande->getUser()->getNom()} {$commande->getUser()->getPrenoms()}</strong>,<br>
                Félicitation, votre commande a été validé par l'équipe de spiral<br>
                Votre N° de commande est: <strong>{$commande->getReference()}</strong>
                ";

            }
            /** Solder de la commande */
            if($status === 5){//On solde la commande, le client récuppère la commande puis on envoi un email au client pour l'informer
                $commande->setValider(true);
                $body = "Bonjour M. <strong>{$commande->getUser()->getNom()} {$commande->getUser()->getPrenoms()}</strong>,<br>
                Votre commande a été soldé et récupérée, merci pour votre confiance<br>
                Votre N° de commande est: <strong>{$commande->getReference()}</strong>
                ";

            }

            $manager->flush();
            $this->flashy->success('Commande modifiée avec succès.');
            return $this->redirectToRoute('admin.commandes.index');
        }

        return $this->render('admin/shops/commandes/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
            'title' => 'Editer commande',
            'libelle_ajouter' => 'Nouvelle commande',
            'libelle_liste' => 'Liste des commandes',
            'current_page' => 'commandes',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.commandes.delete", methods={"DELETE"})
     * @param Request $request
     * @param Commandes $commande
     * @return Response
     */
    public function delete(Request $request, Commandes $commande): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($commande);
            $manager->flush();
            $this->flashy->success('Commande supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.commandes.index');
    }
}
