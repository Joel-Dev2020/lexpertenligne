<?php

namespace App\Extensions\Twig;

use App\Data\ProductsSearchFilterData;
use App\Data\SearchData;
use App\Form\Shop\ProductsSearchFilterFormType;
use App\Form\Shop\SearchFormType;
use App\Repository\Shop\CategoriesRepository;
use App\Repository\Shop\MetakeywordsRepository;
use App\Repository\Shop\ProductsRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductsExtension extends AbstractExtension
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
     * @var MetakeywordsRepository
     */
    private $metakeywordsRepository;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var ProductsRepository
     */
    private $productsRepository;


    public function __construct(
        Environment $twig,
        CacheInterface $cache,
        ContainerInterface $container,
        RequestStack $requestStack,
        MetakeywordsRepository $metakeywordsRepository,
        CategoriesRepository $categoriesRepository,
        ProductsRepository $productsRepository
    )
    {
        $this->twig = $twig;
        $this->cache = $cache;
        $this->container = $container;
        $this->categoriesRepository = $categoriesRepository;
        $this->metakeywordsRepository = $metakeywordsRepository;
        $this->requestStack = $requestStack;
        $this->productsRepository = $productsRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('products_modals', [$this, 'getProductsModals'], ['is_safe' => ['html']]),
            new TwigFunction('products_tags', [$this, 'getProductsTags'], ['is_safe' => ['html']]),
            new TwigFunction('products_sidebar', [$this, 'getProductsSidebar'], ['is_safe' => ['html']]),
            new TwigFunction('products_byed', [$this, 'getProductsByed'], ['is_safe' => ['html']]),
            new TwigFunction('popular_products', [$this, 'getPopularProducts'], ['is_safe' => ['html']]),
            new TwigFunction('last_products', [$this, 'getLastProducts'], ['is_safe' => ['html']]),
            new TwigFunction('last_products_details', [$this, 'getLastProductsDetails'], ['is_safe' => ['html']]),
            new TwigFunction('features_products', [$this, 'getFeaturesProducts'], ['is_safe' => ['html']]),
            new TwigFunction('vedette_sidebar_products', [$this, 'getVedetteSidebarProducts'], ['is_safe' => ['html']]),
            new TwigFunction('count_seuil_qte_products', [$this, 'getCountSeuilQteProducts'], ['is_safe' => ['html']]),
            new TwigFunction('side_bar', [$this, 'getSidebar'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            //new TwigFilter('ucfirst', [$this, 'getUcfirst']),
        ];
    }

    public function getCountSeuilQteProducts(): string {
        return intval($this->productsRepository->findCountSeuilQteProducts(10));
    }

    public function getSidebar(): string {
        $data = new ProductsSearchFilterData();
        $data->page = $this->requestStack->getCurrentRequest()->get('page', 1);
        $form = $this->createForm(ProductsSearchFilterFormType::class, $data);
        [$min, $max] = $this->productsRepository->findMinMax($data);
        $products = $this->productsRepository->findProductsSearchFilter($data);
        return $this->twig->render('pages/shops/products/partials/__sidebar.html.twig', [
            'form' => $form->createView(),
            'datas' => $data,
            'products' => $products,
            'min' => $min,
            'max' => $max,
        ]);
    }

    public function getProductsModals(?int $limit = null, ?array $filters = [], ?array $orders = []): string {
        if ($limit && $filters && $orders){
            $products = $this->productsRepository->findBy($filters, $orders, $limit);
        }else{
            $products = $this->productsRepository->findBy(['online' => true]);
        }

        return $this->twig->render('pages/shops/products/partials/__modal.html.twig', [
            'products' => $products,
        ]);
    }

    public function getPopularProducts(): string {
        $products = $this->productsRepository->findBy(['online' => true], ['vues' => 'DESC'], 8);
        return $this->twig->render('pages/shops/products/partials/__products.html.twig', [
            'products' => $products,
        ]);
    }

    public function getLastProducts(): string {
        $products = $this->productsRepository->latest(8);
        return $this->twig->render('pages/shops/products/partials/__products.html.twig', [
            'products' => $products,
        ]);
    }

    public function getLastProductsDetails(): string {
        $products = $this->productsRepository->latest(8);
        return $this->twig->render('pages/shops/products/partials/__last_products.html.twig', [
            'products' => $products,
        ]);
    }

    public function getFeaturesProducts(): string {
        $products = $this->productsRepository->features(8);
        return $this->twig->render('pages/shops/products/partials/__products.html.twig', [
            'products' => $products,
        ]);
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
