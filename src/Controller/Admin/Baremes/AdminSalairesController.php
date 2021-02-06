<?php

namespace App\Controller\Admin\Baremes;

use App\Entity\Baremes\Salaires;
use App\Form\Baremes\SalairesType;
use App\Managers\Baremes\SalairesManagers;
use App\Repository\Baremes\SalairesRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/salaires", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminSalairesController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var SalairesManagers
     */
    private $salaireManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    public function __construct(FlashyNotifier $flashy,PaginatorInterface $pagination, SalairesManagers $salaireManagers)
    {
        $this->flashy = $flashy;
        $this->salaireManagers = $salaireManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.salaires.index", methods={"GET"})
     * @param Request $request
     * @param SalairesRepository $salairesRepository
     * @return Response
     */
    public function index(Request $request, SalairesRepository $salairesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $salaires = $this->pagination->paginate(
            $salairesRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );

        return $this->render('admin/baremes/salaires/index.html.twig', [
            'salaires' => $salaires,
            'title' => 'Liste des salaires',
            'libelle_ajouter' => 'Nouveau salaire',
            'current_page' => 'salaires',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/new", name="admin.salaires.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $salaire = new Salaires();
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->salaireManagers->new($salaire);
            $this->flashy->success("Salaire ajouté avec succès.");
            $this->addFlash('success', 'Salaire ajouté avec succès.');
            return $this->redirectToRoute('admin.salaires.new');
        }

        return $this->render('admin/baremes/salaires/new.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
            'title' => 'Nouveau salaire',
            'libelle_liste' => 'Liste des salaires',
            'current_page' => 'salaires',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.salaires.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Salaires $salaire
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Salaires $salaire): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(SalairesType::class, $salaire);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->salaireManagers->edit($salaire);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Salaire modifié avec succès.');
            return $this->redirectToRoute('admin.salaires.edit', [
                'id' => $salaire->getId(),
            ]);
        }

        return $this->render('admin/baremes/salaires/edit.html.twig', [
            'salaire' => $salaire,
            'form' => $form->createView(),
            'title' => 'Editer un salaire',
            'libelle_ajouter' => 'Nouveau salaire',
            'libelle_liste' => 'Liste des salaires',
            'current_page' => 'salaires',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.salaires.delete", methods={"DELETE"})
     * @param Request $request
     * @param Salaires $salaire
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Salaires $salaire): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$salaire->getId(), $request->request->get('_token'))) {
            $this->salaireManagers->delete($salaire);
            $this->flashy->success("Suppression éffectuée.");
            $this->addFlash('success', 'Salaire supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.salaires.index');
    }
}
