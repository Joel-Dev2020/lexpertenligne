<?php

namespace App\Controller\Admin\Formations;

use App\Entity\Formations\Formations;
use App\Entity\Formations\Programformations;
use App\Form\Formations\FormationsType;
use App\Form\Formations\ProgramformationsType;
use App\Managers\Formations\FormationsManagers;
use App\Managers\Formations\ProgramformationsManagers;
use App\Repository\Formations\FormationsRepository;
use App\Repository\Formations\ProgramformationsRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/formations", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminFormationsController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var FormationsManagers
     */
    private $formationManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;
    /**
     * @var ProgramformationsManagers
     */
    private $programformationsManagers;

    /**
     * AdminFormationsController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param ProgramformationsManagers $programformationsManagers
     * @param FormationsManagers $formationManagers
     */
    public function __construct(
        FlashyNotifier $flashy,
        PaginatorInterface $pagination,
        ProgramformationsManagers $programformationsManagers,
        FormationsManagers $formationManagers
    )
    {
        $this->flashy = $flashy;
        $this->formationManagers = $formationManagers;
        $this->pagination = $pagination;
        $this->programformationsManagers = $programformationsManagers;
    }

    /**
     * @Route("/", name="admin.formations.index", methods={"GET"})
     * @param Request $request
     * @param FormationsRepository $formationsRepository
     * @return Response
     */
    public function index(Request $request, FormationsRepository $formationsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $formations = $this->pagination->paginate(
            $formationsRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'title' => 'Liste des formations',
            'libelle_ajouter' => 'Nouvelle formation',
            'current_page' => 'formations',
            'current_global' => 'formations'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.formations.activer")
     * @param Request $request
     * @param Formations $formation
     * @return Response
     */
    public function activer(Request $request, Formations $formation): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->formationManagers->active($formation);
        $this->flashy->success("Formation modifiée avec succès.");
        $this->addFlash('success', 'Formation modifiée avec succès.');
        return $this->redirectToRoute('admin.formations.index');
    }

    /**
     * @Route("/new", name="admin.formations.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $formation = new Formations();
        $form = $this->createForm(FormationsType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formationManagers->new($formation);
            $this->flashy->success("Formation ajoutée avec succès.");
            $this->addFlash('success', 'Formation ajoutée avec succès.');
            return $this->redirectToRoute('admin.formations.new');
        }

        return $this->render('admin/formations/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'title' => 'Ajouter une nouvelle formation',
            'libelle_liste' => 'Liste des formations',
            'current_page' => 'formations_new',
            'current_global' => 'formations'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.formations.show", methods={"GET|POST"})
     * @param Request $request
     * @param Formations $formation
     * @param ProgramformationsRepository $programformationsRepository
     * @return Response
     */
    public function show(Request $request, Formations $formation, ProgramformationsRepository $programformationsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $program = new Programformations();
        $form = $this->createForm(ProgramformationsType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->programformationsManagers->new($program, $formation);
            $this->flashy->success("Programme ajouté avec succès.");
            $this->addFlash('success', 'Programme ajouté avec succès.');
            return $this->redirectToRoute('admin.formations.show', [
                'id' => $formation->getId()
            ]);
        }
        $programs = $programformationsRepository->findPrograms($formation);
        return $this->render('admin/formations/show.html.twig', [
            'formation' => $formation,
            'program' => $program,
            'programs' => $programs,
            'form' => $form->createView(),
            'title' => 'Détails formation',
            'libelle_liste' => 'Liste des formations',
            'libelle_ajouter' => 'Nouvelle formation',
            'current_page' => 'formations',
            'current_global' => 'formations'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.formations.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Formations $formation
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Formations $formation): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(FormationsType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->formationManagers->edit($formation);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Formation modifiée avec succès.');
            return $this->redirectToRoute('admin.formations.edit', [
                'id' => $formation->getId(),
            ]);
        }

        return $this->render('admin/formations/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'title' => 'Editer une formation',
            'libelle_ajouter' => 'Nouvelle formation',
            'libelle_liste' => 'Liste des formations',
            'current_page' => 'formations',
            'current_global' => 'formations'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.formations.delete", methods={"DELETE"})
     * @param Request $request
     * @param Formations $formation
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Formations $formation): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $this->formationManagers->delete($formation);
            $this->flashy->success("Suppression éffectuée.");
            $this->addFlash('success', 'Formation supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.formations.index');
    }
}
