<?php

namespace App\Controller;

use App\Repository\Dossiers\DossiersRepository;
use App\Repository\Pages\CategoriesRepository;
use App\Repository\Pages\PagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    const IDCATTHEME = 4;

    /**
     * @var DossiersRepository
     */
    private $dossiersRepository;
    /**
     * @var CategoriesRepository
     */
    private $categoriespagesRepository;

    /**
     * HomeController constructor.
     * @param DossiersRepository $dossiersRepository
     * @param CategoriesRepository $categoriespagesRepository
     */
    public function __construct(DossiersRepository $dossiersRepository, CategoriesRepository $categoriespagesRepository)
    {
        $this->dossiersRepository = $dossiersRepository;
        $this->categoriespagesRepository = $categoriespagesRepository;
    }

    /**
     * @Route({"fr": "/changer-locale/{locale}", "en": "/change-local/{locale}"}, name="change_locale", schemes={"https"})
     * @param $locale
     * @param Request $request
     * @return Response
     */
    public function changeLocale($locale, Request $request)
    {
        //On stock la langue demandée dans la session
        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);
        //On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Route("/", name="home", schemes={"https"}, options={"expose" = true})
     * @param PagesRepository $pagesRepository
     * @return Response
     */
    public function index(PagesRepository $pagesRepository)
    {
        $dossiers = $this->dossiersRepository->findBy(['online' => true], ['id' => 'DESC'], 4);
        $idcattheme = $this->categoriespagesRepository->find(self::IDCATTHEME);
        $quesepassetils = $pagesRepository->findPagesByCat($idcattheme->getId());
        return $this->render('web/home/index.html.twig', [
            'title' => 'Bienvenue',
            'dossiers' => $dossiers,
            'idcattheme' => $idcattheme,
            'quesepassetils' => $quesepassetils,
            'current_page' => 'home',
            'current_global' => 'home',
        ]);
    }

    /**
     * @Route("/about", name="about", schemes={"https"})
     */
    public function about()
    {
        return $this->render('web/home/about.html.twig', [
            'title' => 'Qui sommes nous',
            'current_page' => 'about',
            'current_global' => 'home',
        ]);
    }

    /**
     * @Route("/abonnements", name="abonnement.index", schemes={"https"})
     */
    public function abonnement()
    {
        return $this->render('web/abonnements/index.html.twig', [
            'title' => 'Nos abonnements',
            'current_page' => 'abonnements',
            'current_global' => 'abonnements',
        ]);
    }

    /**
     * @Route("/home202078632454", name="coming", schemes={"https"})
     */
    public function coming()
    {
        return $this->render('web/home/coming.html.twig', [
            'title' => 'Site en maintenance',
            'current_page' => 'coming',
            'current_global' => 'home',
        ]);
    }
}