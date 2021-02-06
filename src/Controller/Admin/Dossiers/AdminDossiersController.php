<?php

namespace App\Controller\Admin\Dossiers;

use App\Entity\Dossiers\Dossiers;
use App\Form\Dossiers\DossiersType;
use App\Managers\Dossiers\DossiersManagers;
use App\Repository\Dossiers\DossiersRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/dossiers", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminDossiersController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var DossiersManagers
     */
    private $dossierManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminDossiersController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param DossiersManagers $dossierManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination, DossiersManagers $dossierManagers)
    {
        $this->flashy = $flashy;
        $this->dossierManagers = $dossierManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.dossiers.index", methods={"GET"})
     * @param Request $request
     * @param DossiersRepository $dossiersRepository
     * @return Response
     */
    public function index(Request $request, DossiersRepository $dossiersRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $dossiers = $this->pagination->paginate(
            $dossiersRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/dossiers/index.html.twig', [
            'dossiers' => $dossiers,
            'title' => 'Liste des dossiers',
            'libelle_ajouter' => 'Nouveau dossier',
            'current_page' => 'dossiers',
            'current_global' => 'dossiers'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.dossiers.activer")
     * @param Request $request
     * @param Dossiers $dossier
     * @return Response
     */
    public function activer(Request $request, Dossiers $dossier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->dossierManagers->active($dossier);
        $this->flashy->success("Dossier modifié avec succès.");
        $this->addFlash('success', 'Dossier modifié avec succès.');
        return $this->redirectToRoute('admin.dossiers.index');
    }

    /**
     * @Route("/new", name="admin.dossiers.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $dossier = new Dossiers();
        $form = $this->createForm(DossiersType::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dossierManagers->new($dossier);
            $this->flashy->success("Dossier ajouté avec succès.");
            $this->addFlash('success', 'Dossier ajouté avec succès.');
            return $this->redirectToRoute('admin.dossiers.new');
        }

        return $this->render('admin/dossiers/new.html.twig', [
            'dossier' => $dossier,
            'form' => $form->createView(),
            'title' => 'Ajouter un nouvel dossier',
            'libelle_liste' => 'Liste des dossiers',
            'current_page' => 'dossiers_new',
            'current_global' => 'dossiers'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.dossiers.show", methods={"GET"})
     * @param Dossiers $dossier
     * @return Response
     */
    public function show(Dossiers $dossier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/dossiers/show.html.twig', [
            'dossier' => $dossier,
            'title' => 'Détails dossier',
            'libelle_liste' => 'Liste des dossiers',
            'libelle_ajouter' => 'Nouveau dossier',
            'current_page' => 'dossiers',
            'current_global' => 'dossiers'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.dossiers.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Dossiers $dossier
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Dossiers $dossier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(DossiersType::class, $dossier);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->dossierManagers->edit($dossier);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Dossier modifié avec succès.');
            return $this->redirectToRoute('admin.dossiers.edit', [
                'id' => $dossier->getId(),
            ]);
        }

        return $this->render('admin/dossiers/edit.html.twig', [
            'dossier' => $dossier,
            'form' => $form->createView(),
            'title' => 'Editer un dossier',
            'libelle_ajouter' => 'Nouveau dossier',
            'libelle_liste' => 'Liste des dossiers',
            'current_page' => 'dossiers',
            'current_global' => 'dossiers'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.dossiers.delete", methods={"DELETE"})
     * @param Request $request
     * @param Dossiers $dossier
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Dossiers $dossier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$dossier->getId(), $request->request->get('_token'))) {
            $this->dossierManagers->delete($dossier);
            $this->flashy->success("Suppression éffectuée.");
            $this->addFlash('success', 'Dossier supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.dossiers.index');
    }
}
