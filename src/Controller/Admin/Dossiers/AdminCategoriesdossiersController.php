<?php

namespace App\Controller\Admin\Dossiers;

use App\Entity\Dossiers\Categoriesdossiers;
use App\Form\Dossiers\CategoriesdossiersType;
use App\Managers\Dossiers\CategoriedossiersManagers;
use App\Repository\Dossiers\CategoriesdossiersRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/dossiers/categories", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminCategoriesdossiersController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var CategoriedossiersManagers
     */
    private $categoriesManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminCategoriesdossiersController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param CategoriedossiersManagers $categoriesManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination, CategoriedossiersManagers $categoriesManagers)
    {
        $this->flashy = $flashy;
        $this->categoriesManagers = $categoriesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.dossierscategories.index", methods={"GET"})
     * @param Request $request
     * @param CategoriesdossiersRepository $categoriesRepository
     * @return Response
     */
    public function index(Request $request, CategoriesdossiersRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categoriesdossiers();
        $form = $this->createForm(CategoriesdossiersType::class, $categorie);
        $categories = $categories = $this->pagination->paginate(
            $categoriesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/dossiers/categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'title' => 'Liste des catégories',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'current_page' => 'dossierscategories',
            'current_global' => 'dossiers'
        ]);
    }

    /**
     * @Route("/new", name="admin.dossierscategories.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categorie = new Categoriesdossiers();
        $form = $this->createForm(CategoriesdossiersType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->new($categorie);
            $this->flashy->success('Catégorie ajoutée avec succès.');
            $this->addFlash('success', 'Catégorie ajoutée avec succès.');
            return $this->redirectToRoute('admin.dossierscategories.new');
        }

        return $this->render('admin/dossiers/categories/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Nouvelle  catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'dossierscategories_new',
            'current_global' => 'dossiers'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.dossierscategories.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Categoriesdossiers $categorie
     * @return Response
     */
    public function edit(Request $request, Categoriesdossiers $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CategoriesdossiersType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesManagers->edit($categorie);
            $this->flashy->success('Catégorie modifiée avec succès.');
            $this->addFlash('success', 'Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin.dossierscategories.index');
        }

        return $this->render('admin/dossiers/categories/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvelle catégorie',
            'libelle_liste' => 'Liste des catégories',
            'current_page' => 'dossierscategories',
            'current_global' => 'dossiers'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.dossierscategories.delete", methods={"DELETE"})
     * @param Request $request
     * @param Categoriesdossiers $categorie
     * @return Response
     */
    public function delete(Request $request, Categoriesdossiers $categorie): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $this->categoriesManagers->delete($categorie);
            $this->flashy->success('Catégorie supprimée avec succès.');
            $this->addFlash('success', 'Catégorie supprimée ajoutée avec succès.');
        }

        return $this->redirectToRoute('admin.dossierscategories.index');
    }
}
