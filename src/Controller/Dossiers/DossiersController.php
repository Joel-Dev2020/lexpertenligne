<?php

namespace App\Controller\Dossiers;

use App\Entity\Dossiers\Categoriesdossiers;
use App\Entity\Dossiers\Commentairesdossiers;
use App\Entity\Dossiers\Dossiers;
use App\Form\Dossiers\CommentairesdossiersType;
use App\Managers\Dossiers\CommentairesdossiersManagers;
use App\Repository\Dossiers\DossiersRepository;
use App\Services\ViewsServices;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dossiers", schemes={"https"})
 */
class DossiersController extends AbstractController
{

    const NBRE_DOSSIERS = 9;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var DossiersRepository
     */
    private $repository;
    /**
     * @var CommentairesdossiersManagers
     */
    private $commentairesdossiersManagers;
    /**
     * @var ViewsServices
     */
    private $viewsServices;

    /**
     * DossiersController constructor.
     * @param PaginatorInterface $paginator
     * @param DossiersRepository $repository
     * @param ViewsServices $viewsServices
     * @param CommentairesdossiersManagers $commentairesdossiersManagers
     * @param FlashyNotifier $flashy
     */
    public function __construct(
        PaginatorInterface $paginator,
        DossiersRepository $repository,
        ViewsServices $viewsServices,
        CommentairesdossiersManagers $commentairesdossiersManagers,
        FlashyNotifier $flashy)
    {
        $this->paginator = $paginator;
        $this->flashy = $flashy;
        $this->repository = $repository;
        $this->commentairesdossiersManagers = $commentairesdossiersManagers;
        $this->viewsServices = $viewsServices;
    }

    /**
     * @Route("/", name="dossiers.index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $dossiers = $this->getDossiers($request, $this->repository->findBy(['online' => true], ['id' => 'DESC']));
        return $this->render('web/dossiers/index.html.twig', [
            'title' => 'Dossiers',
            'dossiers' => $dossiers,
            'nbredossierperpage' => self::NBRE_DOSSIERS,
            'current_page' => 'dossiers',
            'current_global' => 'dossiers',
        ]);
    }

    /**
     * @Route("/{id}/{slug}", name="dossiers.show")
     * @param $slug
     * @param Dossiers $dossier
     * @param Request $request
     * @return Response
     */
    public function show($slug, Dossiers $dossier, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($dossier->getSlug() !== $slug) {
            return $this->redirectToRoute('dossiers.show', [
                'id' => $dossier->getId(),
                'slug' => $dossier->getSlug(),
            ], 301);
        }
        $this->viewsServices->setViews($dossier);
        $autresDossiers = $this->repository->getRandom($dossier, 3);
        $commentaire = new Commentairesdossiers();
        $form = $this->createForm(CommentairesdossiersType::class, $commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->commentairesdossiersManagers->new($commentaire, $dossier);
            $this->flashy->success('Commentaire ajouté avec succès');
            $this->addFlash('success','Votre commentaire été envoyé avec succès');
            return $this->redirectToRoute('dossiers.show', [
                'id' => $dossier->getId(),
                'slug' => $dossier->getSlug(),
            ]);
        }
        return $this->render('web/dossiers/show.html.twig', [
            'title' => $dossier->getName(),
            'dossier' => $dossier,
            'form' => $form->createView(),
            'autresDossiers' => $autresDossiers,
            'current_page' => 'dossiers',
            'current_global' => 'dossiers',
        ]);
    }

    /**
     * @Route("/categorie/{slug}/{id}", name="dossiers.categorie")
     * @param Request $request
     * @param $slug
     * @param Categoriesdossiers $categorie
     * @return Response
     */
    public function categories(Request $request, $slug, Categoriesdossiers $categorie)
    {
        if ($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('dossiers.categorie', [
                'id' => $categorie->getId(),
                'slug' => $categorie->getSlug(),
            ], 301);
        }
        $dossiers = $this->getDossiers($request, $this->repository->findDossiersbyCat($categorie));
        return $this->render('web/dossiers/index.html.twig', [
            'title' => $categorie->getName(),
            'dossiers' => $dossiers,
            'categorie' => $categorie,
            'current_page' => 'categories',
            'current_global' => 'dossiers',
        ]);
    }

    private function getDossiers($request, $dossiers){
        return $this->paginator->paginate(
            $dossiers, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', self::NBRE_DOSSIERS)/*limit per page*/
        );
    }
}