<?php

namespace App\Extensions\Twig;

use App\Repository\Pages\PagesRepository;
use App\Repository\Dossiers\CommentairesdossiersRepository;
use App\Repository\Shop\CategoriesRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PagesExtension extends AbstractExtension
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
     * @var PagesRepository
     */
    private $pagesRepository;


    /**
     * DossiersExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     * @param PagesRepository $pagesRepository
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        ContainerInterface $container,
        RequestStack $requestStack,
        PagesRepository $pagesRepository
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->requestStack = $requestStack;
        $this->pagesRepository = $pagesRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('last_pages_sidebar', [$this, 'getLastPageSidebar'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            //new TwigFilter('ucfirst', [$this, 'getUcfirst']),
        ];
    }

    public function getLastPageSidebar(): string {
        $pages = $this->pagesRepository->latest(10);
        return $this->twig->render('web/pages/partials/last_pages_sidebar.html.twig', [
            'pages' => $pages
        ]);
    }

    public function getCountPages(): string {
        return count($this->pagesRepository->findAll());
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
