<?php

namespace App\Controller\Admin\Formations;

use App\Entity\Formations\Categoriesformations;
use App\Form\Formations\CategoriesformationsType;
use App\Managers\Formations\CategorieformationsManagers;
use App\Repository\Formations\CategoriesformationsRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/formations/categories", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminCategoriesformationsController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var CategorieformationsManagers
     */
    private $categoriesManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminCategoriesformationsController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param CategorieformationsManagers $categoriesManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination, CategorieformationsManagers $categoriesManagers)
    {
        $this->flashy = $flashy;
        $this->categoriesManagers = $categoriesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.formationscategories.index", methods={"GET"})
     * @param Request $request
     * @param CategoriesformationsRepository $categoriesRepository
     * @return Response
     */
    public function index(Request $request, CategoriesformationsRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categoriesformations();
        $form = $this->createForm(CategoriesformationsType::class, $categorie);
        $categories = $categories = $this->pagination->paginate(
            $categoriesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/formations/categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'title' => 'Liste des catégories',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'current_page' => 'formationscategories',
            'current_global' => 'formations'
        ]);
    }

    /**
     * @Route("/new", name="admin.formationscategories.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categoriesformations();
        $form = $this->createForm(CategoriesformationsType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->new($categorie);
            $this->flashy->success('Catégorie ajoutée avec succès.');
            $this->addFlash('success', 'Catégorie ajoutée avec succès.');
            return $this->redirectToRoute('admin.formationscategories.new');
        }

        return $this->render('admin/formations/categories/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Nouvelle  catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'formationscategories_new',
            'current_global' => 'formations'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.formationscategories.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Categoriesformations $categorie
     * @return Response
     */
    public function edit(Request $request, Categoriesformations $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CategoriesformationsType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->edit($categorie);
            $this->flashy->success('Catégorie modifiée avec succès.');
            $this->addFlash('success', 'Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin.formationscategories.index');
        }

        return $this->render('admin/formations/categories/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'formationscategories',
            'current_global' => 'formations'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.formationscategories.delete", methods={"DELETE"})
     * @param Request $request
     * @param Categoriesformations $categorie
     * @return Response
     */
    public function delete(Request $request, Categoriesformations $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $this->categoriesManagers->delete($categorie);
            $this->flashy->success('Catégorie supprimée avec succès.');
            $this->addFlash('success', 'Catégorie supprimée ajoutée avec succès.');
        }

        return $this->redirectToRoute('admin.formationscategories.index');
    }
}
