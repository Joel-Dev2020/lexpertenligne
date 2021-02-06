<?php

namespace App\Controller\Admin\Blogs;

use App\Entity\Blogs\Categoriesblogs;
use App\Form\Blogs\CategoriesblogsType;
use App\Managers\Blogs\CategorieblogsManagers;
use App\Repository\Blogs\CategoriesblogsRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/blog/categories", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminCategoriesblogsController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var CategorieblogsManagers
     */
    private $categoriesManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    public function __construct(
        FlashyNotifier $flashy,
        PaginatorInterface $pagination,
        CategorieblogsManagers $categoriesManagers
    )
    {
        $this->flashy = $flashy;
        $this->categoriesManagers = $categoriesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.blogscategories.index", methods={"GET"})
     * @param Request $request
     * @param CategoriesblogsRepository $categoriesRepository
     * @return Response
     */
    public function index(Request $request, CategoriesblogsRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categoriesblogs();
        $form = $this->createForm(CategoriesblogsType::class, $categorie);
        $categories = $this->pagination->paginate(
            $categoriesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/blogs/categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'title' => 'Liste des catégories',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'current_page' => 'blogscategories',
            'current_global' => 'blogs'
        ]);
    }

    /**
     * @Route("/new", name="admin.blogscategories.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categoriesblogs();
        $form = $this->createForm(CategoriesblogsType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->new($categorie);
            $this->flashy->success('Catégorie ajoutée avec succès.');
            $this->addFlash('success', 'Catégorie ajoutée avec succès.');
            return $this->redirectToRoute('admin.blogscategories.new');
        }

        return $this->render('admin/blogs/categories/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Nouvelle  catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'blogscategories_new',
            'current_global' => 'blogs'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.blogscategories.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Categoriesblogs $categorie
     * @return Response
     */
    public function edit(Request $request, Categoriesblogs $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CategoriesblogsType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->edit($categorie);
            $this->flashy->success('Catégorie modifiée avec succès.');
            $this->addFlash('success', 'Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin.blogscategories.index');
        }

        return $this->render('admin/blogs/categories/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'blogscategories',
            'current_global' => 'blogs'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.blogscategories.delete", methods={"DELETE"})
     * @param Request $request
     * @param Categoriesblogs $categorie
     * @return Response
     */
    public function delete(Request $request, Categoriesblogs $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $this->categoriesManagers->delete($categorie);
            $this->flashy->success('Catégorie supprimée avec succès.');
            $this->addFlash('success', 'Catégorie supprimée ajoutée avec succès.');
        }

        return $this->redirectToRoute('admin.blogscategories.index');
    }
}
