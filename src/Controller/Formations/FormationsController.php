<?php

namespace App\Controller\Formations;

use App\Entity\Formations\Categoriesformations;
use App\Entity\Formations\Commentairesformations;
use App\Entity\Formations\Formations;
use App\Form\Formations\CommentairesformationsType;
use App\Managers\Formations\CommentairesformationsManagers;
use App\Repository\Formations\FormationsRepository;
use App\Repository\Formations\ProgramformationsRepository;
use App\Services\ViewsServices;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formations", schemes={"https"})
 */
class FormationsController extends AbstractController
{

    const NBRE_FORMATION = 5;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var FormationsRepository
     */
    private $repository;
    /**
     * @var CommentairesformationsManagers
     */
    private $commentairesformationsManagers;
    /**
     * @var ViewsServices
     */
    private $viewsServices;

    /**
     * FormationsController constructor.
     * @param PaginatorInterface $paginator
     * @param FormationsRepository $repository
     * @param CommentairesformationsManagers $commentairesformationsManagers
     * @param ViewsServices $viewsServices
     * @param FlashyNotifier $flashy
     */
    public function __construct(
        PaginatorInterface $paginator,
        FormationsRepository $repository,
        CommentairesformationsManagers $commentairesformationsManagers,
        ViewsServices $viewsServices,
        FlashyNotifier $flashy)
    {
        $this->paginator = $paginator;
        $this->flashy = $flashy;
        $this->repository = $repository;
        $this->commentairesformationsManagers = $commentairesformationsManagers;
        $this->viewsServices = $viewsServices;
    }

    /**
     * @Route("/", name="formations.index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $formations = $this->getFormations($request, $this->repository->findBy(['online' => true], ['id' => 'DESC']));
        return $this->render('web/formations/index.html.twig', [
            'title' => 'Nos formations',
            'formations' => $formations,
            'nbreformationperpage' => self::NBRE_FORMATION,
            'current_page' => 'formations',
            'current_global' => 'formations',
        ]);
    }

    /**
     * @Route("/{id}/{slug}", name="formations.show")
     * @param $slug
     * @param Formations $formation
     * @param Request $request
     * @return Response
     */
    public function show($slug, Formations $formation, Request $request, ProgramformationsRepository $programformationsRepository)
    {
        if (!$this->getUser()){
            $this->addFlash('success','Vous devez vous connecter pour voir ce contenu');
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        }
        if ($formation->getSlug() !== $slug) {
            return $this->redirectToRoute('formations.show', [
                'id' => $formation->getId(),
                'slug' => $formation->getSlug(),
            ], 301);
        }
        $this->viewsServices->setViews($formation);
        $autresFormations = $this->repository->getRandom($formation, 3);
        $commentaire = new Commentairesformations();
        $form = $this->createForm(CommentairesformationsType::class, $commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->commentairesformationsManagers->new($commentaire, $formation);
            $this->flashy->success('Commentaire ajouté avec succès');
            $this->addFlash('success','Votre commentaire été envoyé avec succès');
            return $this->redirectToRoute('formations.show', [
                'id' => $formation->getId(),
                'slug' => $formation->getSlug(),
            ]);
        }
        $programs = $programformationsRepository->findPrograms($formation);
        return $this->render('web/formations/show.html.twig', [
            'title' => $formation->getName(),
            'formation' => $formation,
            'programs' => $programs,
            'form' => $form->createView(),
            'autresFormations' => $autresFormations,
            'current_page' => 'formations',
            'current_global' => 'formations',
        ]);
    }

    /**
     * @Route("/categorie/{slug}/{id}", name="formations.categorie")
     * @param Request $request
     * @param $slug
     * @param Categoriesformations $categorie
     * @return Response
     */
    public function categories(Request $request, $slug, Categoriesformations $categorie)
    {
        if ($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('formations.categorie', [
                'id' => $categorie->getId(),
                'slug' => $categorie->getSlug(),
            ], 301);
        }
        $formations = $this->getFormations($request, $this->repository->findFormationsbyCat($categorie));
        return $this->render('web/formations/index.html.twig', [
            'title' => $categorie->getName(),
            'formations' => $formations,
            'categorie' => $categorie,
            'current_page' => 'categories',
            'current_global' => 'formations',
        ]);
    }

    private function getFormations($request, $formations){
        return $this->paginator->paginate(
            $formations, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', self::NBRE_FORMATION)/*limit per page*/
        );
    }
}