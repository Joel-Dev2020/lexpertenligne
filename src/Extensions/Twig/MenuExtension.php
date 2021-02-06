<?php

namespace App\Extensions\Twig;

use App\Entity\Pages\Categories;
use App\Repository\Documents\CategoriesdocumentsRepository;
use App\Repository\Pages\CategoriesRepository;
use App\Repository\Pages\PagesRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\TwigFunction;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Cache\CacheInterface;

class MenuExtension extends AbstractExtension
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
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var CategoriesRepository
     */
    private $categoriesRepository;
    /**
     * @var PagesRepository
     */
    private $pagesRepository;
    /**
     * @var CategoriesdocumentsRepository
     */
    private $categoriesdocumentsRepository;

    /**
     * MenuExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param PagesRepository $pagesRepository
     * @param CategoriesdocumentsRepository $categoriesdocumentsRepository
     * @param UrlGeneratorInterface $router
     * @param ContainerInterface $container
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        PagesRepository $pagesRepository,
        CategoriesdocumentsRepository $categoriesdocumentsRepository,
        UrlGeneratorInterface $router,
        ContainerInterface $container
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->router = $router;
        $this->pagesRepository = $pagesRepository;
        $this->categoriesdocumentsRepository = $categoriesdocumentsRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pages_nav', [$this, 'getPagesNav'], ['is_safe' => ['html']]),
            new TwigFunction('docs_nav', [$this, 'getDocsNav'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param int $categorie_id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getPagesNav(int $categorie_id): string {
        return $this->twig->render('layouts/front/partials/navs/droit_travail_nav.html.twig', [
            'pages' => $this->pagesRepository->findPagesByCat($categorie_id)
        ]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getDocsNav(): string {
        return $this->twig->render('layouts/front/partials/navs/documents_nav.html.twig', [
            'categories' => $this->categoriesdocumentsRepository->findBy([], ['ordre' => 'ASC'])
        ]);
    }
}