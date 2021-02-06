<?php

namespace App\Controller\Admin\Baremes;

use App\Entity\Baremes\Categories;
use App\Form\Baremes\CategoriesType;
use App\Managers\Baremes\CategoriesManagers;
use App\Repository\Baremes\CategoriesRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/baremes/categories", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminCategoriesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var CategoriesManagers
     */
    private $categoriesManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    public function __construct(
        FlashyNotifier $flashy,
        PaginatorInterface $pagination,
        CategoriesManagers $categoriesManagers
    )
    {
        $this->flashy = $flashy;
        $this->categoriesManagers = $categoriesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.baremescategories.index", methods={"GET"})
     * @param Request $request
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    public function index(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categories();
        $form = $this->createForm(CategoriesType::class, $categorie);
        $categories = $this->pagination->paginate(
            $categoriesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/baremes/categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'title' => 'Liste des catégories',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'current_page' => 'baremescategories',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/new", name="admin.baremescategories.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categories();
        $form = $this->createForm(CategoriesType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->new($categorie);
            $this->flashy->success('Catégorie ajoutée avec succès.');
            $this->addFlash('success', 'Catégorie ajoutée avec succès.');
            return $this->redirectToRoute('admin.baremescategories.new');
        }

        return $this->render('admin/baremes/categories/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Nouvelle  catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'baremescategories_new',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.baremescategories.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Categories $categorie
     * @return Response
     */
    public function edit(Request $request, Categories $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CategoriesType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->edit($categorie);
            $this->flashy->success('Catégorie modifiée avec succès.');
            $this->addFlash('success', 'Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin.baremescategories.index');
        }

        return $this->render('admin/baremes/categories/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'baremescategories',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.baremescategories.delete", methods={"DELETE"})
     * @param Request $request
     * @param Categories $categorie
     * @return Response
     */
    public function delete(Request $request, Categories $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $this->categoriesManagers->delete($categorie);
            $this->flashy->success('Catégorie supprimée avec succès.');
            $this->addFlash('success', 'Catégorie supprimée ajoutée avec succès.');
        }

        return $this->redirectToRoute('admin.baremescategories.index');
    }
}
