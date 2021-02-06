<?php

namespace App\Extensions\Twig;

use App\Entity\Shop\Adresses;
use App\Repository\Shop\AdressesRepository;
use App\Services\Carts\CartsServices;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Cache\CacheInterface;

class CartExtension extends AbstractExtension
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
     * @var CartsServices
     */
    private $cartsServices;
    /**
     * @var AdressesRepository
     */
    private $adressesRepository;
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        CartsServices $cartsServices,
        Security $security,
        AdressesRepository $adressesRepository,
        ContainerInterface $container
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->cartsServices = $cartsServices;
        $this->adressesRepository = $adressesRepository;
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('floating_cart', [$this, 'getFloatingCart'], ['is_safe' => ['html']]),
            new TwigFunction('cart_table', [$this, 'getCartTable'], ['is_safe' => ['html']]),
            new TwigFunction('cart_table_recap', [$this, 'getCartTableRecap'], ['is_safe' => ['html']]),
            new TwigFunction('panier_count', [$this, 'getPanierCount'], ['is_safe' => ['html']]),
            new TwigFunction('total_cart', [$this, 'getTotalCart'], ['is_safe' => ['html']]),
            new TwigFunction('total_panier', [$this, 'getTotalPanier'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('devise', [$this, 'getPriceDevise'], ['is_safe' => ['html']]),
            new TwigFilter('francs', [$this, 'getPriceDeviseSimple'], ['is_safe' => ['html']]),
        ];
    }

    public function getPriceDevise(int $price): string
    {
        return number_format($price, 0, ' ', ' ').' <span class="product-Price-currencySymbol">CFA</span>';
    }

    public function getPriceDeviseSimple(int $price): string
    {
        return number_format($price, 0, ' ', ' ').' <small style="font-size: 13px;">Fcfa</small>';
    }

    public function getFloatingCart(): string {
        $products = $this->cartsServices->getFullCart();
        return $this->twig->render('pages/shops/paniers/partials/__float_cart.html.twig', [
            'items' => $products,
            'totalht' => $this->cartsServices->getTotalHT(),
            'totaltva' => $this->cartsServices->getTotalTva(),
            'totalttc' => $this->cartsServices->getTotalTTC(),
        ]);
    }

    public function getCartTable(): string {
        $products = $this->cartsServices->getFullCart();
        return $this->twig->render('pages/shops/paniers/partials/__cart_table.html.twig', [
            'products' =>  $products,
            'totalht' =>  $this->cartsServices->getTotalHT(),
            'totaltva' => $this->cartsServices->getTotalTva(),
            'totalttc' => $this->cartsServices->getTotalTTC(),
        ]);
    }

    public function getCartTableRecap(): string {
        $products = $this->cartsServices->getFullCart();
        $adresses = $this->adressesRepository->findBy(['user' => $this->security->getUser()]);
        return $this->twig->render('pages/shops/paniers/partials/__cart_table_recap.html.twig', [
            'products' =>  $products,
            'adresses' =>  $adresses,
            'totalht' =>  $this->cartsServices->getTotalHT(),
            'totaltva' => $this->cartsServices->getTotalTva(),
            'totalttc' => $this->cartsServices->getTotalTTC(),
        ]);
    }

    public function getTotalCart(): string {
        return $this->twig->render('pages/shops/paniers/partials/__total.html.twig', [
            'totalht' => $this->cartsServices->getTotalHT(),
            'totaltva' => $this->cartsServices->getTotalTva(),
            'totalttc' => $this->cartsServices->getTotalTTC(),
        ]);
    }

    public function getPanierCount(): string {
        return $this->cartsServices->getTotalCount();
    }

    public function getTotalPanier(): string {
        return $this->cartsServices->getTotalTTC();
    }

}