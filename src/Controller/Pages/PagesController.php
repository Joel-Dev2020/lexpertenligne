<?php

namespace App\Controller\Pages;

use App\Entity\Pages\Categories;
use App\Entity\Pages\Pages;
use App\Repository\Pages\PagesRepository;
use App\Services\ViewsServices;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", schemes={"https"})
 */
class PagesController extends AbstractController
{
    /**
     * @var PagesRepository
     */
    private $pagesRepository;
    /**
     * @var ViewsServices
     */
    private $viewsServices;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * PagesController constructor.
     * @param PagesRepository $pagesRepository
     * @param PaginatorInterface $pagination
     * @param ViewsServices $viewsServices
     */
    public function __construct(PagesRepository $pagesRepository,PaginatorInterface $pagination, ViewsServices $viewsServices)
    {
        $this->pagesRepository = $pagesRepository;
        $this->viewsServices = $viewsServices;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/{slug}/{id}", name="pages.show", defaults={"parent": "categories"})
     * @param $slug
     * @param Pages $page
     * @return RedirectResponse|Response
     */
    public function show($slug, Pages $page)
    {
        if ($page->getSlug() !== $slug) {
            return $this->redirectToRoute('pages.show', [
                'id' => $page->getId(),
                'slug' => $page->getSlug(),
            ], 301);
        }
        $this->viewsServices->setViews($page);
        $autresPages = $this->pagesRepository->findRand(null, 3, $page);
        return $this->render('web/pages/show.html.twig', [
            'title' => $page->getName(),
            'page' => $page,
            'autresPages' => $autresPages,
            'current_page' => 'pages',
            'current_global' => 'pages',
        ]);
    }

    /**
     * @Route("/categorie/{id}/{slug}", name="pages.categories.show")
     * @param Request $request
     * @param $slug
     * @param Categories $categorie
     * @return RedirectResponse|Response
     */
    public function showCategories(Request $request, $slug, Categories $categorie)
    {
        if ($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('pages.categories.show', [
                'slug' => $categorie->getSlug(),
                'id' => $categorie->getId()
            ], 301);
        }

        $pages = $this->pagination->paginate(
            $this->pagesRepository->findPagesByCat( $categorie->getId()), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 5)/*limit per page*/
        );
        return $this->render('web/pages/categorie.html.twig', [
            'title' => $categorie->getName(),
            'pages' => $pages,
            'current_page' => 'pages',
            'current_global' => 'pages',
        ]);
    }

    /**
     * @Route("/policy/{slug}", name="pages.policy.show")
     * @param $slug
     * @return RedirectResponse|Response
     */
    public function policy($slug)
    {
        $page = $this->pagesRepository->findOneBy(['slug' => $slug]);
        if ($page->getSlug() !== $slug) {
            return $this->redirectToRoute('pages.policy.show', [
                'slug' => $page->getSlug(),
            ], 301);
        }
        return $this->render('web/pages/policy/show.html.twig', [
            'title' => $page->getName(),
            'page' => $page,
            'current_page' => 'policy',
            'current_global' => 'spirals',
        ]);
    }
}