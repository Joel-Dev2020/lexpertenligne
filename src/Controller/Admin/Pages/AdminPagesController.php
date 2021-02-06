<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Pages\Pages;
use App\Form\Pages\PagesType;
use App\Managers\Pages\PagesManagers;
use App\Repository\Pages\PagesRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/pages", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminPagesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var PagesManagers
     */
    private $pagesManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminPagesController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param PagesManagers $pagesManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination, PagesManagers $pagesManagers)
    {
        $this->flashy = $flashy;
        $this->pagesManagers = $pagesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.pages.index", methods={"GET","POST"})
     * @param Request $request
     * @param PagesRepository $pagesRepository
     * @return Response
     */
    public function index(Request $request, PagesRepository $pagesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $page = new Pages();
        $form = $this->createForm(PagesType::class, $page);
        $pages = $categories = $this->pagination->paginate(
            $pagesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/pages/index.html.twig', [
            'pages' => $pages,
            'form' => $form->createView(),
            'title' => 'Liste des pages',
            'libelle_ajouter' => 'Nouvelle page',
            'current_page' => 'pages',
            'current_global' => 'pages'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.pages.activer")
     * @param Pages $page
     * @return Response
     */
    public function pageActive(Pages $page): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->pagesManagers->active($page);
        $this->flashy->success("Post modifié avec succès.");
        return $this->redirectToRoute('admin.pages.index');
    }

    /**
     * @Route("/new", name="admin.pages.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $page = new Pages();
        $form = $this->createForm(PagesType::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->pagesManagers->new($page);
            $this->flashy->success('Page ajoutée avec succès.');
            $this->addFlash('success','Page ajoutée avec succès.');
            return $this->redirectToRoute('admin.pages.new');
        }

        return $this->render('admin/pages/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'title' => 'Nouvelle  page',
            'libelle_liste' => 'Liste des pages',
            'current_page' => 'pages',
            'current_global' => 'pages'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.pages.show", methods={"GET"})
     * @param Pages $page
     * @return Response
     */
    public function show(Pages $page): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/pages/show.html.twig', [
            'page' => $page,
            'title' => 'Détails de la page',
            'libelle_liste' => 'Liste des pages',
            'libelle_ajouter' => 'Nouvelle page',
            'current_page' => 'pages',
            'current_global' => 'pages'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.pages.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Pages $page
     * @return Response
     */
    public function edit(Request $request, Pages $page): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(PagesType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->pagesManagers->edit($page);
            $this->flashy->success('Page modifiée avec succès.');
            $this->addFlash('success','Page modifiée avec succès.');
            return $this->redirectToRoute('admin.pages.index');
        }

        return $this->render('admin/pages/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvelle  page',
            'libelle_liste' => 'Liste des pages',
            'current_page' => 'pages',
            'current_global' => 'pages'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.pages.delete", methods={"DELETE"})
     * @param Request $request
     * @param Pages $page
     * @return Response
     */
    public function delete(Request $request, Pages $page): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $this->pagesManagers->delete($page);
            $this->flashy->success('Page supprimée avec succès.');
            $this->addFlash('success','Page supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.pages.index');
    }
}
