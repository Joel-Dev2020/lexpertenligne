<?php

namespace App\Extensions\Twig;

use App\Repository\Formations\CategoriesformationsRepository;
use App\Repository\Formations\CommentairesformationsRepository;
use App\Repository\Formations\FormationsRepository;
use App\Repository\Shop\CategoriesRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormationsExtension extends AbstractExtension
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
     * @var FormationsRepository
     */
    private $formationsRepository;
    /**
     * @var CommentairesformationsRepository
     */
    private $commentairesformationsRepository;


    /**
     * FormationsExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     * @param CategoriesformationsRepository $categoriesRepository
     * @param CommentairesformationsRepository $commentairesformationsRepository
     * @param FormationsRepository $formationsRepository
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        ContainerInterface $container,
        RequestStack $requestStack,
        CategoriesformationsRepository $categoriesRepository,
        CommentairesformationsRepository $commentairesformationsRepository,
        FormationsRepository $formationsRepository
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->categoriesRepository = $categoriesRepository;
        $this->requestStack = $requestStack;
        $this->formationsRepository = $formationsRepository;
        $this->commentairesformationsRepository = $commentairesformationsRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('admin_count_formations', [$this, 'getCountFormations'], ['is_safe' => ['html']]),
            new TwigFunction('admin_count_categories_formations', [$this, 'getCountCategories'], ['is_safe' => ['html']]),
            new TwigFunction('admin_count_comments_formations', [$this, 'getCountComments'], ['is_safe' => ['html']]),
            new TwigFunction('last_formations', [$this, 'getLastFormations'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            //new TwigFilter('ucfirst', [$this, 'getUcfirst']),
        ];
    }

    public function getCountFormations(): string {
        return count($this->formationsRepository->findAll());
    }

    public function getCountCategories(): string {
        return count($this->categoriesRepository->findAll());
    }

    public function getCountComments(): string {
        return count($this->commentairesformationsRepository->findAll());
    }

    public function getLastFormations(): string {
        $formations = $this->formationsRepository->latest(4);
        return $this->twig->render('web/formations/partials/__last_formations.html.twig', [
            'formations' => $formations,
        ]);
    }

    private function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }
}
