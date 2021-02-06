<?php

namespace App\Extensions\Twig;

use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\CommentairesblogsRepository;
use App\Repository\Dossiers\CommentairesdossiersRepository;
use App\Repository\Dossiers\DossiersRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\TwigFunction;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Cache\CacheInterface;

class CountsExtension extends AbstractExtension
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
     * @var BlogsRepository
     */
    private $blogsRepository;
    /**
     * @var DossiersRepository
     */
    private $dossiersRepository;
    /**
     * @var CommentairesdossiersRepository
     */
    private $commentairesdossiersRepository;
    /**
     * @var CommentairesblogsRepository
     */
    private $commentairesblogsRepository;

    /**
     * MenuExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param UrlGeneratorInterface $router
     * @param BlogsRepository $blogsRepository
     * @param DossiersRepository $dossiersRepository
     * @param CommentairesdossiersRepository $commentairesdossiersRepository
     * @param CommentairesblogsRepository $commentairesblogsRepository
     * @param ContainerInterface $container
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        UrlGeneratorInterface $router,
        BlogsRepository $blogsRepository,
        DossiersRepository $dossiersRepository,
        CommentairesdossiersRepository $commentairesdossiersRepository,
        CommentairesblogsRepository $commentairesblogsRepository,
        ContainerInterface $container
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->router = $router;
        $this->blogsRepository = $blogsRepository;
        $this->dossiersRepository = $dossiersRepository;
        $this->commentairesdossiersRepository = $commentairesdossiersRepository;
        $this->commentairesblogsRepository = $commentairesblogsRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('count_blogs', [$this, 'getCountBlogs'], ['is_safe' => ['html']]),
            new TwigFunction('count_dossiers', [$this, 'getCountDossiers'], ['is_safe' => ['html']]),
        ];
    }

    public function getCountBlogs(): array {
        $blogs = [
            'countblogs' => count($this->blogsRepository->findBy(['online' => true])) ?? 0,
            'countcomments' => count($this->commentairesblogsRepository->findAll()) ?? 0,
            'countvues' => $this->blogsRepository->findViews() ?? 0,
            'countpublishedtoday' => $this->blogsRepository->findPublishedToDay() ?? 0,
        ];
        return $blogs ?? [];
    }

    public function getCountDossiers(): array {
        $dossiers = [
            'countdossiers' => count($this->dossiersRepository->findBy(['online' => true])) ?? 0,
            'countcomments' => count($this->commentairesdossiersRepository->findAll()) ?? 0,
            'countvues' => $this->dossiersRepository->findViews() ?? 0,
            'countpublishedtoday' => $this->blogsRepository->findPublishedToDay() ?? 0,
        ];
        return $dossiers ?? [];
    }
}