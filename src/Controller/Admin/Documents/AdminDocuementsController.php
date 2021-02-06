<?php

namespace App\Controller\Admin\Documents;

use App\Entity\Documents\Documents;
use App\Form\Documents\DocumentsType;
use App\Managers\Documents\DocumentsManagers;
use App\Repository\Documents\DocumentsRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/documents", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminDocuementsController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var DocumentsManagers
     */
    private $documentsManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminDocumentsController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param DocumentsManagers $documentsManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination, DocumentsManagers $documentsManagers)
    {
        $this->flashy = $flashy;
        $this->documentsManagers = $documentsManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.documents.index", methods={"GET","POST"})
     * @param Request $request
     * @param DocumentsRepository $documentsRepository
     * @return Response
     */
    public function index(Request $request, DocumentsRepository $documentsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document);
        $documents = $categories = $this->pagination->paginate(
            $documentsRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*document number*/,
            $request->query->getInt('limit', 10)/*limit per document*/
        );
        return $this->render('admin/documents/index.html.twig', [
            'documents' => $documents,
            'form' => $form->createView(),
            'title' => 'Liste des documents',
            'libelle_ajouter' => 'Nouveau document',
            'current_page' => 'documents',
            'current_global' => 'documents'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.documents.activer")
     * @param Documents $document
     * @return Response
     */
    public function documentActive(Documents $document): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->documentsManagers->active($document);
        $this->flashy->success("Document modifié avec succès.");
        return $this->redirectToRoute('admin.documents.index');
    }

    /**
     * @Route("/new", name="admin.documents.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->documentsManagers->new($document);
            $this->flashy->success('Document ajouté avec succès.');
            $this->addFlash('success','Document ajouté avec succès.');
            return $this->redirectToRoute('admin.documents.new');
        }

        return $this->render('admin/documents/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'title' => 'Nouveau  document',
            'libelle_liste' => 'Liste des documents',
            'current_page' => 'documents',
            'current_global' => 'documents'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.documents.show", methods={"GET"})
     * @param Documents $document
     * @return Response
     */
    public function show(Documents $document): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/documents/show.html.twig', [
            'document' => $document,
            'title' => 'Détails de la document',
            'libelle_liste' => 'Liste des documents',
            'libelle_ajouter' => 'Nouveau document',
            'current_page' => 'documents',
            'current_global' => 'documents'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.documents.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Documents $document
     * @return Response
     */
    public function edit(Request $request, Documents $document): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->documentsManagers->edit($document);
            $this->flashy->success('Document modifié avec succès.');
            $this->addFlash('success','Document modifié avec succès.');
            return $this->redirectToRoute('admin.documents.index');
        }

        return $this->render('admin/documents/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouveau document',
            'libelle_liste' => 'Liste des documents',
            'current_page' => 'documents',
            'current_global' => 'documents'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.documents.delete", methods={"DELETE"})
     * @param Request $request
     * @param Documents $document
     * @return Response
     */
    public function delete(Request $request, Documents $document): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $this->documentsManagers->delete($document);
            $this->flashy->success('Document supprimé avec succès.');
            $this->addFlash('success','Document supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.documents.index');
    }
}
