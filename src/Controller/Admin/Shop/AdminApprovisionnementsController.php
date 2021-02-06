<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Approvisionnements;
use App\Entity\Shop\Products;
use App\Form\Shop\ApprovisionnementsType;
use App\Repository\Shop\ApprovisionnementsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products/approvisionnements", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminApprovisionnementsController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/", name="admin.approvisionnements.index", methods={"GET"})
     * @param ApprovisionnementsRepository $approvisionnementsRepository
     * @return Response
     */
    public function index(ApprovisionnementsRepository $approvisionnementsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $approvisionnement = new Approvisionnements();
        $form = $this->createForm(ApprovisionnementsType::class, $approvisionnement);
        return $this->render('admin/shops/products/approvisionnements/index.html.twig', [
            'approvisionnements' => $approvisionnementsRepository->findAll(),
            'form' => $form->createView(),
            'title' => 'Liste des approvisionnements',
            'libelle_ajouter' => 'Nouvel approvisionnement',
            'current_page' => 'approvisionnements',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/new", name="admin.approvisionnements.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $approvisionnement = new Approvisionnements();
        $form = $this->createForm(ApprovisionnementsType::class, $approvisionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $product Products
             */
            $product = $this->getDoctrine()->getRepository(Products::class)->find($form->get('products')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $approvisionnement->setUser($this->getUser());
            //On reccuper la quantité du produit pour la mettre dans le champ oldqty de la table approvisionnement
            $approvisionnement->setOldqty($product->getQuantity());
            $entityManager->persist($approvisionnement);
            $entityManager->flush();
            //On additionne la nouvelle quantité ajoutée à la qté du produit et on fait une mise à jour
            $product->setQuantity($form->get('newqty')->getData() + $product->getQuantity());
            $entityManager->flush();
            $this->flashy->success('Approvisionnement ajouté avec succès.');
            return $this->redirectToRoute('admin.approvisionnements.index');
        }

        return $this->render('admin/shops/products/approvisionnements/new.html.twig', [
            'approvisionnement' => $approvisionnement,
            'form' => $form->createView(),
            'title' => 'Nouvel approvisionnement',
            'libelle_liste' => 'Liste des approvisionnements',
            'current_page' => 'approvisionnements',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.approvisionnements.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Approvisionnements $approvisionnement
     * @return Response
     */
    public function edit(Request $request, Approvisionnements $approvisionnement): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(ApprovisionnementsType::class, $approvisionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $product Products
             */
            $product = $this->getDoctrine()->getRepository(Products::class)->find($approvisionnement->getProducts()->getId());
            //On additionne la nouvelle quantité ajoutée à la qté du produit et on fait une mise à jour
            $product->setQuantity($approvisionnement->getOldqty() + $approvisionnement->getNewqty());
            $this->getDoctrine()->getManager()->flush();
            $this->flashy->success('Approvisionnement modifié avec succès.');
            return $this->redirectToRoute('admin.approvisionnements.index');
        }

        return $this->render('admin/shops/products/approvisionnements/edit.html.twig', [
            'approvisionnement' => $approvisionnement,
            'form' => $form->createView(),
            'title' => 'Editer approvisionnements',
            'libelle_ajouter' => 'Nouvel approvisionnement',
            'libelle_liste' => 'Liste des approvisionnementss',
            'current_page' => 'approvisionnements',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.approvisionnements.delete", methods={"DELETE"})
     * @param Request $request
     * @param Approvisionnements $approvisionnement
     * @return Response
     */
    public function delete(Request $request, Approvisionnements $approvisionnement): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$approvisionnement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($approvisionnement);
            $entityManager->flush();
            $this->flashy->success('Approvisionnement supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.approvisionnements.index');
    }
}
