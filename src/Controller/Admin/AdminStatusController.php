<?php

namespace App\Controller\Admin;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/status", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminStatusController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminStatusController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     */
    public function __construct(
        FlashyNotifier $flashy,
        PaginatorInterface $pagination
    )
    {
        $this->flashy = $flashy;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.status.index", methods={"GET"})
     * @param Request $request
     * @param StatusRepository $statusRepository
     * @return Response
     */
    public function index(Request $request, StatusRepository $statusRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $statuses = $this->pagination->paginate(
            $statusRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/status/index.html.twig', [
            'statuses' => $statuses,
            'form' => $form->createView(),
            'title' => 'Liste des status',
            'libelle_ajouter' => 'Nouveau status',
            'current_page' => 'status',
            'current_global' => 'parametres'
        ]);
    }

    /**
     * @Route("/new", name="admin.status.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($status);
            $entityManager->flush();
            $this->flashy->success('Status ajouté avec succès.');
            return $this->redirectToRoute('admin.status.index');
        }

        return $this->render('admin/status/new.html.twig', [
            'status' => $status,
            'form' => $form->createView(),
            'title' => 'Nouveau status',
            'libelle_liste' => 'Liste des status',
            'current_page' => 'status',
            'current_global' => 'parametres'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.status.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Status $status
     * @return Response
     */
    public function edit(Request $request, Status $status): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->flashy->success('Status modifié avec succès.');
            return $this->redirectToRoute('admin.status.index');
        }

        return $this->render('admin/status/edit.html.twig', [
            'status' => $status,
            'form' => $form->createView(),
            'title' => 'Editer status',
            'libelle_ajouter' => 'Nouveau status',
            'libelle_liste' => 'Liste des status',
            'current_page' => 'status',
            'current_global' => 'parametres'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.status.delete", methods={"DELETE"})
     * @param Request $request
     * @param Status $status
     * @return Response
     */
    public function delete(Request $request, Status $status): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$status->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($status);
            $entityManager->flush();
            $this->flashy->success('Status supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.status.index');
    }
}
