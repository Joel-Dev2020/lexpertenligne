<?php

namespace App\Controller\Admin\Baremes;

use App\Entity\Baremes\Salariers;
use App\Form\Baremes\SalariersType;
use App\Managers\Baremes\SalariersManagers;
use App\Repository\Baremes\SalariersRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/salariers", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminSalariersController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var SalariersManagers
     */
    private $salarierManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    public function __construct(FlashyNotifier $flashy,PaginatorInterface $pagination, SalariersManagers $salarierManagers)
    {
        $this->flashy = $flashy;
        $this->salarierManagers = $salarierManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.salariers.index", methods={"GET"})
     * @param Request $request
     * @param SalariersRepository $salariersRepository
     * @return Response
     */
    public function index(Request $request, SalariersRepository $salariersRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $salariers = $this->pagination->paginate(
            $salariersRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );

        return $this->render('admin/baremes/salariers/index.html.twig', [
            'salariers' => $salariers,
            'title' => 'Liste des salariers',
            'libelle_ajouter' => 'Nouveau type salarier',
            'current_page' => 'salariers',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/new", name="admin.salariers.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $salarier = new Salariers();
        $form = $this->createForm(SalariersType::class, $salarier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->salarierManagers->new($salarier);
            $this->flashy->success("Type salarier ajouté avec succès.");
            $this->addFlash('success', 'Type salarier ajouté avec succès.');
            return $this->redirectToRoute('admin.salariers.new');
        }

        return $this->render('admin/baremes/salariers/new.html.twig', [
            'salarier' => $salarier,
            'form' => $form->createView(),
            'title' => 'Nouveau type salarier',
            'libelle_liste' => 'Liste des salariers',
            'current_page' => 'salariers',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.salariers.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Salariers $salarier
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Salariers $salarier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(SalariersType::class, $salarier);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->salarierManagers->edit($salarier);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Type salarier modifié avec succès.');
            return $this->redirectToRoute('admin.salariers.edit', [
                'id' => $salarier->getId(),
            ]);
        }

        return $this->render('admin/baremes/salariers/edit.html.twig', [
            'salarier' => $salarier,
            'form' => $form->createView(),
            'title' => 'Editer un salarier',
            'libelle_ajouter' => 'Nouveau type salarier',
            'libelle_liste' => 'Liste des types salariers',
            'current_page' => 'salariers',
            'current_global' => 'simulations'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.salariers.delete", methods={"DELETE"})
     * @param Request $request
     * @param Salariers $salarier
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Salariers $salarier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$salarier->getId(), $request->request->get('_token'))) {
            $this->salarierManagers->delete($salarier);
            $this->flashy->success("Suppression éffectuée.");
            $this->addFlash('success', 'Type salarier supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.salariers.index');
    }
}
