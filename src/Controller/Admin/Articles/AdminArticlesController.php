<?php

namespace App\Controller\Admin\Articles;

use App\Entity\Articles\Articles;
use App\Form\Articles\ArticlesType;
use App\Managers\Articles\ArticlesManagers;
use App\Repository\Articles\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/articles", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminArticlesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var ArticlesManagers
     */
    private $articlesManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminArticlesController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param ArticlesManagers $articlesManagers
     */
    public function __construct(FlashyNotifier $flashy, PaginatorInterface $pagination, ArticlesManagers $articlesManagers)
    {
        $this->flashy = $flashy;
        $this->articlesManagers = $articlesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.articles.index", methods={"GET","POST"})
     * @param Request $request
     * @param ArticlesRepository $articlesRepository
     * @return Response
     */
    public function index(Request $request, ArticlesRepository $articlesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $articles = $categories = $this->pagination->paginate(
            $articlesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*article number*/,
            $request->query->getInt('limit', 10)/*limit per article*/
        );
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
            'title' => 'Liste des articles',
            'libelle_ajouter' => 'Nouvel article',
            'current_page' => 'articles',
            'current_global' => 'articles'
        ]);
    }

    /**
     * @Route("/new", name="admin.articles.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->articlesManagers->new($article);
            $this->flashy->success('Article ajouté avec succès.');
            $this->addFlash('success','Article ajouté avec succès.');
            return $this->redirectToRoute('admin.articles.new');
        }

        return $this->render('admin/articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'title' => 'Nouvel article',
            'libelle_liste' => 'Liste des articles',
            'current_page' => 'articles',
            'current_global' => 'articles'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.articles.show", methods={"GET"})
     * @param Articles $article
     * @return Response
     */
    public function show(Articles $article): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/articles/show.html.twig', [
            'article' => $article,
            'title' => 'Détails de la article',
            'libelle_liste' => 'Liste des articles',
            'libelle_ajouter' => 'Nouvel article',
            'current_page' => 'articles',
            'current_global' => 'articles'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.articles.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Articles $article
     * @return Response
     */
    public function edit(Request $request, Articles $article): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articlesManagers->edit($article);
            $this->flashy->success('Article modifié avec succès.');
            $this->addFlash('success','Article modifié avec succès.');
            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvel article',
            'libelle_liste' => 'Liste des articles',
            'current_page' => 'articles',
            'current_global' => 'articles'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.articles.delete", methods={"DELETE"})
     * @param Request $request
     * @param Articles $article
     * @return Response
     */
    public function delete(Request $request, Articles $article): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->articlesManagers->delete($article);
            $this->flashy->success('Article supprimé avec succès.');
            $this->addFlash('success','Article supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.articles.index');
    }
}
