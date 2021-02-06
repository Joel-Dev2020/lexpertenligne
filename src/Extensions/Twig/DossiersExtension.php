<?php

namespace App\Extensions\Twig;

use App\Repository\Dossiers\CategoriesdossiersRepository;
use App\Repository\Dossiers\CommentairesdossiersRepository;
use App\Repository\Dossiers\DossiersRepository;
use App\Repository\Shop\CategoriesRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DossiersExtension extends AbstractExtension
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
     * @var DossiersRepository
     */
    private $dossiersRepository;
    /**
     * @var CommentairesdossiersRepository
     */
    private $commentairesdossiersRepository;


    /**
     * DossiersExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     * @param CategoriesdossiersRepository $categoriesRepository
     * @param CommentairesdossiersRepository $commentairesdossiersRepository
     * @param DossiersRepository $dossiersRepository
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        ContainerInterface $container,
        RequestStack $requestStack,
        CategoriesdossiersRepository $categoriesRepository,
        CommentairesdossiersRepository $commentairesdossiersRepository,
        DossiersRepository $dossiersRepository
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->categoriesRepository = $categoriesRepository;
        $this->requestStack = $requestStack;
        $this->dossiersRepository = $dossiersRepository;
        $this->commentairesdossiersRepository = $commentairesdossiersRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('admin_count_dossiers', [$this, 'getCountDossiers'], ['is_safe' => ['html']]),
            new TwigFunction('admin_count_categories_dossiers', [$this, 'getCountCategories'], ['is_safe' => ['html']]),
            new TwigFunction('admin_count_comments_dossiers', [$this, 'getCountComments'], ['is_safe' => ['html']]),
            new TwigFunction('last_dossiers_sidebar', [$this, 'getLastDossiersSidebar'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            //new TwigFilter('ucfirst', [$this, 'getUcfirst']),
        ];
    }

    public function getLastDossiersSidebar(): string {
        $dossiers = $this->dossiersRepository->latest(10);
        return $this->twig->render('web/dossiers/partials/last_dossiers_sidebar.html.twig', [
            'dossiers' => $dossiers
        ]);
    }

    public function getCountDossiers(): string {
        return count($this->dossiersRepository->findAll());
    }

    public function getCountCategories(): string {
        return count($this->categoriesRepository->findAll());
    }

    public function getCountComments(): string {
        return count($this->commentairesdossiersRepository->findAll());
    }

    public function getVedetteSidebarProducts(): string {
        $products = $this->productsRepository->features(3);
        return $this->twig->render('pages/shops/products/partials/__sidebar_vedette_products.html.twig', [
            'products' => $products,
        ]);
    }

    private function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }
}
