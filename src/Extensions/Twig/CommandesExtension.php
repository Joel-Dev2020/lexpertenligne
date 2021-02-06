<?php

namespace App\Extensions\Twig;

use App\Entity\Shop\Commandes;
use App\Repository\CategoriesRepository;
use App\Repository\Shop\CommandesRepository;
use App\Repository\Shop\MetakeywordsRepository;
use App\Repository\Shop\ProductsRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CommandesExtension extends AbstractExtension
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
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var CommandesRepository
     */
    private $commandesRepository;
    /**
     * @var Security
     */
    private $security;


    /**
     * CommandesExtension constructor.
     * @param Environment $twig
     * @param CacheInterface $cache
     * @param ContainerInterface $container
     * @param Security $security
     * @param RequestStack $requestStack
     * @param CommandesRepository $commandesRepository
     */
    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        ContainerInterface $container,
        Security $security,
        RequestStack $requestStack,
        CommandesRepository $commandesRepository
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->requestStack = $requestStack;
        $this->commandesRepository = $commandesRepository;
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_commandes', [$this, 'getUserCommandes'], ['is_safe' => ['html']]),
            new TwigFunction('user_adresses', [$this, 'getUserAdresses'], ['is_safe' => ['html']]),
            new TwigFunction('user_wishlist', [$this, 'getUserWishlist'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            //new TwigFilter('ucfirst', [$this, 'getUcfirst']),
        ];
    }

    public function getUserCommandes(): string {
        $commandes = $this->security->getUser()->getCommandes();
        return $this->twig->render('pages/profil/partials/__commande.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    public function getUserAdresses(): string {
        $adresses = $this->security->getUser()->getAdresses();
        return $this->twig->render('pages/profil/partials/__adresses.html.twig', [
            'adresses' => $adresses,
        ]);
    }

    public function getUserWishlist(): string {
        $wishlists = $this->security->getUser()->getWishlists();
        return $this->twig->render('pages/profil/partials/__wishlist.html.twig', [
            'wishlists' => $wishlists,
        ]);
    }

    private function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }
}
