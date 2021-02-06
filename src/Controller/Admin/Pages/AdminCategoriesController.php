<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Pages\Categories;
use App\Form\Pages\CategoriesType;
use App\Managers\Pages\CategoriesManagers;
use App\Repository\Pages\CategoriesRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/pages/categories", schemes={"https"})
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
     * @Route("/", name="admin.pagescategories.index", methods={"GET"})
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
        return $this->render('admin/pages/categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'title' => 'Liste des catégories',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'current_page' => 'pagescategories',
            'current_global' => 'pages'
        ]);
    }

    /**
     * @Route("/new", name="admin.pagescategories.new", methods={"GET","POST"})
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
            $this->addFlash('success','Catégorie ajoutée avec succès.');
            return $this->redirectToRoute('admin.pagescategories.new');
        }

        return $this->render('admin/pages/categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'title' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'pagescategories',
            'current_global' => 'pages'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.pagescategories.edit", methods={"GET","POST"})
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
            $this->addFlash('success','Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin.pagescategories.index');
        }

        return $this->render('admin/pages/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'pagescategories',
            'current_global' => 'pages'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.pagescategories.delete", methods={"DELETE"})
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
            $this->addFlash('success','Catégorie supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.pagescategories.index');
    }
}
