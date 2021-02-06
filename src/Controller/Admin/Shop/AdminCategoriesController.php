<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Categories;
use App\Form\Shop\CategoriesType;
use App\Managers\Products\CategoriesManagers;
use App\Repository\Shop\CategoriesRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products/categories", schemes={"https"})
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

    /**
     * AdminCategoriesController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param CategoriesManagers $categoriesManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination, CategoriesManagers $categoriesManagers)
    {
        $this->flashy = $flashy;
        $this->categoriesManagers = $categoriesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.categories.index", methods={"GET"})
     * @param Request $request
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    public function index(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $categories = $categories = $this->pagination->paginate(
            $categoriesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/shops/categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'title' => 'Liste des catégories',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'current_page' => 'productscategories',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/new", name="admin.categories.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->new($category);
            $this->flashy->success('Catégorie ajoutée avec succès.');
            return $this->redirectToRoute('admin.categories.new');
        }

        return $this->render('admin/shops/categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'title' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'productscategories',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.categories.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Categories $category
     * @return Response
     */
    public function edit(Request $request, Categories $category): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->edit($category);
            $this->flashy->success('Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render('admin/shops/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'productscategories',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.categories.delete", methods={"DELETE"})
     * @param Request $request
     * @param Categories $category
     * @return Response
     */
    public function delete(Request $request, Categories $category): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->categoriesManagers->delete($category);
            $this->flashy->success('Catégorie supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.categories.index');
    }
}
