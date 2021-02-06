<?php

namespace App\Controller\Documents;

use App\Entity\Documents\Categoriesdocuments;
use App\Repository\Documents\CategoriesdocumentsRepository;
use App\Repository\Documents\DocumentsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documents", schemes={"https"})
 */
class DocumentsController extends AbstractController
{

    const NBRE_DOCUMENT = 6;
    const NBRE_CATEGORIE = 6;

    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var DocumentsRepository
     */
    private $documentsRepository;
    /**
     * @var CategoriesdocumentsRepository
     */
    private $categoriesdocumentsRepository;

    /**
     * DocumentsController constructor.
     * @param PaginatorInterface $paginator
     * @param DocumentsRepository $documentsRepository,
     * @param CategoriesdocumentsRepository $categoriesdocumentsRepository
     */
    public function __construct(
        PaginatorInterface $paginator,
        DocumentsRepository $documentsRepository,
        CategoriesdocumentsRepository $categoriesdocumentsRepository
    )
    {
        $this->paginator = $paginator;
        $this->documentsRepository = $documentsRepository;
        $this->categoriesdocumentsRepository = $categoriesdocumentsRepository;
    }

    /**
     * @Route("/", name="documents.index", schemes={"https"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $categories = $this->getDatas($request, $this->categoriesdocumentsRepository->findBy([], ['ordre' => 'ASC']), self::NBRE_CATEGORIE);
        return $this->render('web/documents/index.html.twig', [
            'title' => 'Documents',
            'categories' => $categories,
            'current_page' => 'documents',
            'current_global' => 'documents',
        ]);
    }

    /**
     * @Route("/categorie/{slug}/{id}", name="documents.categorie")
     * @param Request $request
     * @param $slug
     * @param Categoriesdocuments $categorie
     * @return Response
     */
    public function categories(Request $request, $slug, Categoriesdocuments $categorie)
    {
        if ($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('documents.categorie', [
                'id' => $categorie->getId(),
                'slug' => $categorie->getSlug(),
            ], 301);
        }
        $documents = $this->getDatas($request, $this->documentsRepository->findDocsbyCat($categorie), self::NBRE_DOCUMENT);
        $title = "{$categorie->getName()} ({$categorie->getDocuments()->count()} document(s))";
        return $this->render('web/documents/categories.html.twig', [
            'title' => $title,
            'documents' => $documents,
            'categorie' => $categorie,
            'current_page' => 'documents',
            'current_global' => 'documents',
        ]);
    }

    private function getDatas($request, $datas, $limit){
        return $this->paginator->paginate(
            $datas, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', $limit)/*limit per page*/
        );
    }
}