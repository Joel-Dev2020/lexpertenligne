<?php

namespace App\Controller\Blogs;

use App\Entity\Blogs\Commentairesblogs;
use App\Entity\Blogs\SearchData;
use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\Categoriesblogs;
use App\Entity\User;
use App\Form\Blogs\CommentairesblogsType;
use App\Form\Blogs\SearchFormType;
use App\Managers\Blogs\CommentairesblogsManagers;
use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\VotesblogsRepository;
use App\Services\ViewsServices;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actualites", schemes={"https"})
 */
class BlogsController extends AbstractController
{

    const NBRE_BLOG = 5;

    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var BlogsRepository
     */
    private $repository;
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var CommentairesblogsManagers
     */
    private $commentairesblogsManagers;
    /**
     * @var ViewsServices
     */
    private $viewsServices;

    /**
     * BlogsController constructor.
     * @param PaginatorInterface $paginator
     * @param BlogsRepository $repository
     * @param ViewsServices $viewsServices
     * @param CommentairesblogsManagers $commentairesblogsManagers
     * @param FlashyNotifier $flashy
     */
    public function __construct(
        PaginatorInterface $paginator,
        BlogsRepository $repository,
        ViewsServices $viewsServices,
        CommentairesblogsManagers $commentairesblogsManagers,
        FlashyNotifier $flashy)
    {
        $this->paginator = $paginator;
        $this->repository = $repository;
        $this->flashy = $flashy;
        $this->commentairesblogsManagers = $commentairesblogsManagers;
        $this->viewsServices = $viewsServices;
    }

    /**
     * @Route("/", name="blogs.index")
     * @param Request $request
     * @param VotesblogsRepository $votesblogsRepository
     * @return Response
     */
    public function index(Request $request, VotesblogsRepository $votesblogsRepository)
    {
        $blogs = $this->getBlogs($request, $this->repository->findBy(['online' => true], ['id' => 'DESC']));
        $likes = [];
        $disLiked = [];
        foreach ($blogs as $blog){
            $likes[$blog->getId()] = count($votesblogsRepository->findBy(['blogs' => $blog, 'vote' => true]));
            if ($this->getUser()){
                $disLiked[$blog->getId()] = $votesblogsRepository->findUserDisliked($this->getUser(), $blog);
            }else{
                $disLiked[$blog->getId()] = false;
            }
        }

        return $this->render('web/blogs/index.html.twig', [
            'title' => 'Actualités',
            'likes' => $likes,
            'disLiked' => $disLiked,
            'nbreblogperpage' => self::NBRE_BLOG,
            'blogs' => $blogs,
            'current_page' => 'blogs',
            'current_global' => 'blogs',
        ]);
    }

    /**
     * @Route("/{id}/{slug}", name="blogs.show")
     * @param $slug
     * @param Blogs $blog
     * @param Request $request
     * @param VotesblogsRepository $votesblogsRepository
     * @return Response
     */
    public function show($slug, Blogs $blog, Request $request, VotesblogsRepository $votesblogsRepository)
    {
        if ($blog->getSlug() !== $slug) {
            return $this->redirectToRoute('blogs.show', [
                'id' => $blog->getId(),
                'slug' => $blog->getSlug(),
            ], 301);
        }
        $this->viewsServices->setViews($blog);
        $autresBlogs = $this->repository->getRandom($blog, 3);
        $commentaire = new Commentairesblogs();
        $form = $this->createForm(CommentairesblogsType::class, $commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->commentairesblogsManagers->new($commentaire, $blog);
            $this->flashy->success('Commentaire ajouté avec succès');
            $this->addFlash('success','Votre commentaire été envoyé avec succès');
            return $this->redirectToRoute('blogs.show', [
                'id' => $blog->getId(),
                'slug' => $blog->getSlug(),
            ]);
        }
        $likes = count($votesblogsRepository->findBy(['blogs' => $blog, 'vote' => true]));
        $disLiked = $votesblogsRepository->findUserDisliked($this->getUser(), $blog);
        return $this->render('web/blogs/show.html.twig', [
            'title' => $blog->getName(),
            'blog' => $blog,
            'likes' => $likes,
            'disLiked' => $disLiked,
            'form' => $form->createView(),
            'autresBlogs' => $autresBlogs,
            'current_page' => 'blogs',
            'current_global' => 'blogs',
        ]);
    }

    /**
     * @Route("/categorie/{slug}/{id}", name="blogs.categorie")
     * @param Request $request
     * @param $slug
     * @param Categoriesblogs $categorie
     * @return Response
     */
    public function categories(Request $request, $slug, Categoriesblogs $categorie)
    {
        if ($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('blogs.categorie', [
                'id' => $categorie->getId(),
                'slug' => $categorie->getSlug(),
            ], 301);
        }
        $blogs = $this->getBlogs($request, $this->repository->findBlogsbyCat($categorie));
        return $this->render('web/blogs/index.html.twig', [
            'title' => $categorie->getName(),
            'blogs' => $blogs,
            'categorie' => $categorie,
            'current_page' => 'categories',
            'current_global' => 'blogs',
        ]);
    }

    /**
     * @Route("/query", name="blogs.search.index", defaults={"title":"Recherche dans le blog"})
     * @param $title
     * @param Request $request
     * @param BlogsRepository $blogsRepository
     * @return Response
     */
    public function search($title,Request $request, BlogsRepository $blogsRepository)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $blogs = [];

        if($form->isSubmitted() && $form->isValid()){
            $blogs = $blogsRepository->findSearch($data);
        }

        return $this->render('web/blogs/search.html.twig', [
            'title' => $title,
            'search' => $data->q,
            'blogs' => $blogs,
            'form' => $form->createView(),
            'current_page' => 'search',
            'current_global' => 'blogs'
        ]);
    }

    private function getBlogs($request, $blogs){
        return $this->paginator->paginate(
            $blogs, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', self::NBRE_BLOG)/*limit per page*/
        );
    }
}