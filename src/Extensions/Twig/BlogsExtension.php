<?php

namespace App\Extensions\Twig;

use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\CategoriesblogsRepository;
use App\Repository\Blogs\CommentairesblogsRepository;
use App\Repository\Dossiers\CommentairesdossiersRepository;
use App\Repository\Shop\CategoriesRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlogsExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var CategoriesRepository
     */
    private $categoriesRepository;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var CommentairesdossiersRepository
     */
    private $commentairesdossiersRepository;
    /**
     * @var BlogsRepository
     */
    private $blogsRepository;


    /**
     * DossiersExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     * @param CategoriesblogsRepository $categoriesRepository
     * @param CommentairesblogsRepository $commentairesdossiersRepository
     * @param BlogsRepository $blogsRepository
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        ContainerInterface $container,
        RequestStack $requestStack,
        CategoriesblogsRepository $categoriesRepository,
        CommentairesblogsRepository $commentairesdossiersRepository,
        BlogsRepository $blogsRepository
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->categoriesRepository = $categoriesRepository;
        $this->requestStack = $requestStack;
        $this->commentairesdossiersRepository = $commentairesdossiersRepository;
        $this->blogsRepository = $blogsRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('admin_count_blogs', [$this, 'getCountBlogs'], ['is_safe' => ['html']]),
            new TwigFunction('admin_count_categories_blogs', [$this, 'getCountCategories'], ['is_safe' => ['html']]),
            new TwigFunction('admin_count_comments_blogs', [$this, 'getCountComments'], ['is_safe' => ['html']]),
            new TwigFunction('last_blogs_sidebar', [$this, 'getLastBlogSidebar'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            //new TwigFilter('ucfirst', [$this, 'getUcfirst']),
        ];
    }

    public function getLastBlogSidebar(): string {
        $blogs = $this->blogsRepository->latest(10);
        return $this->twig->render('web/blogs/partials/last_news_sidebar.html.twig', [
            'blogs' => $blogs
        ]);
    }

    public function getCountBlogs(): string {
        return count($this->blogsRepository->findAll());
    }

    public function getCountCategories(): string {
        return count($this->categoriesRepository->findAll());
    }

    public function getCountComments(): string {
        return count($this->commentairesdossiersRepository->findAll());
    }

    public function getVedetteSidebarProducts(): string {
        return $this->twig->render('pages/shops/products/partials/__sidebar_vedette_products.html.twig');
    }

    private function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }
}
