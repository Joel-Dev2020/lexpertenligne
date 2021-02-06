<?php

namespace App\Controller\Admin\Baremes;

use App\Entity\Baremes\Secteurs;
use App\Form\Baremes\SecteursType;
use App\Managers\Baremes\SecteursManagers;
use App\Repository\Baremes\SecteursRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/secteurs", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminSecteursController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var SecteursManagers
     */
    private $secteurManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    public function __construct(FlashyNotifier $flashy,PaginatorInterface $pagination, SecteursManagers $secteurManagers)
    {
        $this->flashy = $flashy;
        $this->secteurManagers = $secteurManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.secteurs.index", methods={"GET"})
     * @param Request $request
     * @param SecteursRepository $secteursRepository
     * @return Response
     */
    public function index(Request $request, SecteursRepository $secteursRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $secteurs = $this->pagination->paginate(
            $secteursRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );

        return $this->render('admin/baremes/secteurs/index.html.twig', [
            'secteurs' => $secteurs,
            'title' => 'Liste des secteurs',
            'libelle_ajouter' => 'Nouveau secteur',
            'current_page' => 'secteurs',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/new", name="admin.secteurs.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $secteur = new Secteurs();
        $form = $this->createForm(SecteursType::class, $secteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->secteurManagers->new($secteur);
            $this->flashy->success("Secteur ajouté avec succès.");
            $this->addFlash('success', 'Secteur ajouté avec succès.');
            return $this->redirectToRoute('admin.secteurs.new');
        }

        return $this->render('admin/baremes/secteurs/new.html.twig', [
            'secteur' => $secteur,
            'form' => $form->createView(),
            'title' => 'Nouveau secteur',
            'libelle_liste' => 'Liste des secteurs',
            'current_page' => 'secteurs',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.secteurs.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Secteurs $secteur
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Secteurs $secteur): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(SecteursType::class, $secteur);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->secteurManagers->edit($secteur);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Secteur modifié avec succès.');
            return $this->redirectToRoute('admin.secteurs.edit', [
                'id' => $secteur->getId(),
            ]);
        }

        return $this->render('admin/baremes/secteurs/edit.html.twig', [
            'secteur' => $secteur,
            'form' => $form->createView(),
            'title' => 'Editer un secteur',
            'libelle_ajouter' => 'Nouveau secteur',
            'libelle_liste' => 'Liste des secteurs',
            'current_page' => 'secteurs',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.secteurs.delete", methods={"DELETE"})
     * @param Request $request
     * @param Secteurs $secteur
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Secteurs $secteur): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$secteur->getId(), $request->request->get('_token'))) {
            $this->secteurManagers->delete($secteur);
            $this->flashy->success("Suppression éffectuée.");
            $this->addFlash('success', 'Secteur supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.secteurs.index');
    }
}
