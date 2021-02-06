<?php

namespace App\Controller\Shop;

use App\Entity\Shop\Adresses;
use App\Form\Shop\AdressesType;
use App\Repository\Shop\AdressesRepository;
use App\Services\Carts\CartsServices;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop/adresses", schemes={"https"})
 */
class AdressesController extends AbstractController
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

    public function __construct(
        FlashyNotifier $flashy,
        SessionInterface $session,
        CartsServices $cartsServices,
        AdressesRepository $adressesRepository
    )
    {
        $this->flashy = $flashy;
        $this->session = $session;
        $this->adressesRepository = $adressesRepository;
        $this->cartsServices = $cartsServices;
    }

    /**
     * @Route("/", name="shop.adresses.index", methods={"POST","GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if(!$this->cartsServices->getFullCart()){
            return $this->redirectToRoute('cart.index');
        }

        if (!$this->session->has('livraison')) $this->session->set('livraison', []);
        $livraison = $this->session->get('livraison');

        if (!$this->session->has('facturation')) $this->session->set('facturation', []);
        $facturation = $this->session->get('facturation');

        if($request->request->get('note')){
            if (!$this->session->has('note')) $this->session->set('note', $request->request->get('note'));
        }
        $note = $this->session->get('note');

        $adresse = new Adresses();
        $form = $this->createForm(AdressesType::class, $adresse);
        $adresses = $this->getDoctrine()->getRepository(Adresses::class)->findBy(['user' => $this->getUser()]);

        return $this->render('web/shops/adresses/index.html.twig', [
            'title' => 'Adresses',
            'adresse' => $adresse,
            'note' => $note,
            'livraison' => $livraison,
            'facturation' => $facturation,
            'adresses' => $adresses,
            'form' => $form->createView(),
            'current_page' => 'shop',
            'current_global' => 'shop',
        ]);
    }

    /**
     * @Route("/new", name="add_address", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function add_address(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if(!$this->cartsServices->getFullCart()){
            return $this->redirectToRoute('cart.index');
        }

        if (!$this->session->has('livraison')) $this->session->set('livraison', []);
        $livraison = $this->session->get('livraison');

        if (!$this->session->has('facturation')) $this->session->set('facturation', []);
        $facturation = $this->session->get('facturation');

        if($request->request->get('note')){
            if (!$this->session->has('note')) $this->session->set('note', $request->request->get('note'));
        }
        $note = $this->session->get('note');

        $adresse = new Adresses();
        $form = $this->createForm(AdressesType::class, $adresse);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $adresse->setUser($this->getUser());
            $manager->persist($adresse);
            $manager->flush();
            $this->flashy->success('Adresse ajoutée avec succès');
            $this->addFlash('success', 'Adresse ajoutée avec succès');
            return $this->redirectToRoute('shop.adresses.index');
        }
        $adresses = $this->getDoctrine()->getRepository(Adresses::class)->findBy(['user' => $this->getUser()]);
        return $this->render('web/shops/adresses/index.html.twig', [
            'title' => 'Adresses',
            'adresse' => $adresse,
            'livraison' => $livraison,
            'facturation' => $facturation,
            'adresses' => $adresses,
            'form' => $form->createView(),
            'current_page' => 'shop',
            'current_global' => 'shop',
        ]);
    }

    /**
     * @Route("/set/livraison", name="livraison.set", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function setAddLivraison(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->session->has('livraison')) $this->session->set('livraison', []);
        $getAdress = intval($request->request->get('adresse_livraison'));
        $adress = $this->adressesRepository->find($getAdress);
        if($adress){
            $this->session->set('livraison', $adress);
            $this->flashy->success('Adresse de livraison selectionnée');
        }
        return $this->redirectToRoute('shop.adresses.index');
    }

    /**
     * @Route("/set/facturation", name="facturation.set", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function setAddFacturation(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->session->has('facturation')) $this->session->set('facturation', []);
        $getAdress = intval($request->request->get('adresse_facturation'));
        $adress = $this->adressesRepository->find($getAdress);
        if($adress){
            $this->session->set('facturation', $adress);
            $this->flashy->success('Adresse de facturation selectionnée');
        }
        return $this->redirectToRoute('shop.adresses.index');
    }

    /**
     * @Route("/delete/livraison/{id}", name="livraison.delete", methods={"GET"})
     * @param Request $request
     * @param Adresses $adresses
     * @return Response
     */
    public function deleteAdressLivraison(Request $request, Adresses $adresses)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $manager = $this->getDoctrine()->getManager();
        if($adresses){
            if (!$this->session->has('livraison')) $this->session->set('livraison', []);
            $this->session->remove('livraison');
            $manager->remove($adresses);
            $manager->flush();
            $this->flashy->success('Adresse de livraison supprimée');
        }

        return $this->redirectToRoute('shop.adresses.index');
    }

    /**
     * @Route("/delete/facturation/{id}", name="facturation.delete", methods={"GET"})
     * @param Request $request
     * @param Adresses $adresses
     * @return Response
     */
    public function deleteAdressFacturation(Request $request, Adresses $adresses)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $manager = $this->getDoctrine()->getManager();
        if($adresses){
            if (!$this->session->has('facturation')) $this->session->set('facturation', []);
            $this->session->remove('facturation');
            $manager->remove($adresses);
            $manager->flush();
            $this->flashy->success('Adresse de facturation supprimée');
        }

        return $this->redirectToRoute('shop.adresses.index');
    }

    /**
     * @Route("/{id}", name="admin.adresses.delete", methods={"DELETE"})
     * @param Request $request
     * @param Adresses $adresse
     * @return Response
     */
    public function delete(Request $request, Adresses $adresse): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($adresse);
            $manager->flush();
            $this->flashy->success('Adresse supprimée avec succès.');
        }

        return $this->redirectToRoute('profile.edit', [
            'id' => $this->getUser()->getId(),
        ]);
    }
}