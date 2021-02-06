<?php

namespace App\Controller\Shop;

use App\Data\ProductsSearchFilterData;
use App\Entity\Shop\Categories;
use App\Entity\Shop\Commentaireproducts;
use App\Entity\Shop\Metakeywords;
use App\Entity\Shop\Products;
use App\Entity\Shop\Wishlists;
use App\Entity\User;
use App\Form\Shop\ProductsSearchFilterFormType;
use App\Form\Shop\CommentaireproductsType;
use App\Repository\ParametresRepository;
use App\Repository\Shop\ProductsRepository;
use App\Repository\Shop\WishlistsRepository;
use App\Services\ViewsServices;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop", schemes={"https"})
 */
class ShopController extends AbstractController
{
    /**
     * @var ViewsServices
     */
    private $viewsServices;
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var WishlistsRepository
     */
    private $wishlistsRepository;
    /**
     * @var ProductsRepository
     */
    private $productsRepository;
    /**
     * @var ParametresRepository
     */
    private $parametresRepository;

    /**
     * ShopController constructor.
     * @param ViewsServices $viewsServices
     * @param FlashyNotifier $flashy
     * @param ParametresRepository $parametresRepository
     * @param ProductsRepository $productsRepository
     * @param WishlistsRepository $wishlistsRepository
     */
    public function __construct(
        ViewsServices $viewsServices,
        FlashyNotifier $flashy,
        ParametresRepository $parametresRepository,
        ProductsRepository $productsRepository,
        WishlistsRepository $wishlistsRepository
    )
    {
        $this->viewsServices = $viewsServices;
        $this->flashy = $flashy;
        $this->wishlistsRepository = $wishlistsRepository;
        $this->productsRepository = $productsRepository;
        $this->parametresRepository = $parametresRepository;
    }

    /**
     * @Route("/", name="shop.index")
     * @param Request $request
     * @param ContainerInterface $container
     * @return Response
     */
    public function index(Request $request, ContainerInterface $container)
    {
        $app = $this->parametresRepository->find($container->getParameter('company_id'))->getName();
        $data = new ProductsSearchFilterData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(ProductsSearchFilterFormType::class, $data);
        $form->handleRequest($request);
        [$min, $max] = $this->productsRepository->findMinMax($data);
        $products = $this->productsRepository->findProductsSearchFilter($data);
        return $this->render('web/shops/products/index.html.twig', [
            'title' => "Boutique $app",
            'products' => $products,
            'datas' => $data,
            'min' => $min,
            'max' => $max,
            'form' => $form->createView(),
            'current_page' => 'shop',
            'current_global' => 'shop',
        ]);
    }

    /**
     * @Route("/categories/{slug}/{id}", name="shop.products.categories")
     * @param Request $request
     * @param $slug
     * @param Categories $categorie
     * @return Response
     */
    public function categories(Request $request, $slug, Categories $categorie)
    {
        if ($categorie->getSlug() !== $slug) {
            return $this->redirectToRoute('shop.products.categories', [
                'id' => $categorie->getId(),
                'slug' => $categorie->getSlug(),
            ], 301);
        }
        $products = $this->productsRepository->findCategories($categorie, $request->get('page', 1));
        return $this->render('web/shops/products/index.html.twig', [
            'title' => $categorie->getName(),
            'categories' => $categorie,
            'products' => $products,
            'current_page' => 'shop',
            'current_global' => 'shop',
        ]);
    }

    /**
     * @Route("/tags/{slug}/{id}", name="shop.products.tags")
     * @param Request $request
     * @param $slug
     * @param Metakeywords $metakeywords
     * @return Response
     */
    public function productsByTags(Request $request, $slug, Metakeywords $metakeywords)
    {
        if ($metakeywords->getSlug() !== $slug) {
            return $this->redirectToRoute('shop.products.tags', [
                'id' => $metakeywords->getId(),
                'slug' => $metakeywords->getSlug(),
            ], 301);
        }
        $products = $this->productsRepository->findTags($metakeywords, $request->get('page', 1));
        return $this->render('web/shops/products/index.html.twig', [
            'title' => $metakeywords->getName(),
            'metakeywords' => $metakeywords,
            'products' => $products,
            'current_page' => 'shop',
            'current_global' => 'shop',
        ]);
    }

    /**
     * @Route("/products/show/{slug}/{id}", name="shop.products.show")
     * @param Request $request
     * @param $slug
     * @param Products $product
     * @return RedirectResponse|Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function show(Request $request, $slug, Products $product)
    {
        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute('shop.products.show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ], 301);
        }
        $this->viewsServices->setProductsViews($product);
        $relatedProducts = $this->productsRepository->getRandom(
            $product,
            10
        );

        $associatedProducts = [];
        foreach ($product->getAssociation() as $product){
            $associatedProducts[$product->getId()] = $product;
        }

        $wishlistUser = [];
        foreach ($relatedProducts as $prod){
            $wishlistUser[$prod->getId()] = $this->checkWishlist($prod);
        }

        $commentaire = new Commentaireproducts();
        $form = $this->createForm(CommentaireproductsType::class, $commentaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var $user User
             */
            $user = $this->getUser();
            $manager = $this->getDoctrine()->getManager();
            $commentaire->setUser($user);
            $commentaire->setProducts($product);
            $commentaire->setEnabled(false);
            $manager->persist($commentaire);
            $manager->flush();
            $this->flashy->success('Commentaire ajoutée avec succès');
            return $this->redirectToRoute('shop.products.show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ]);
        }
        $prevProduct = $this->productsRepository->prev($product);
        $nextProduct = $this->productsRepository->next($product);
        return $this->render('web/shops/products/details.html.twig', [
            'title' => $product->getName(),
            'form' => $form->createView(),
            'product' => $product,
            'next' => $nextProduct,
            'prev' => $prevProduct,
            'associatedProducts' => $associatedProducts,
            'wishlistUser1' => $this->checkWishlist($product),
            'wishlistUser' => $wishlistUser,
            'relatedProducts' => $relatedProducts,
            'current_page' => 'shop',
            'current_global' => 'shop',
        ]);
    }

    /**
     * @param Products $product
     * @return bool
     */
    private function checkWishlist(Products $product){
        $wishlistUser = false;

        /**
         * @var $wishlists Wishlists
         */
        $wishlists = $this->wishlistsRepository->findBy(['user' => $this->getUser()]);
        foreach ($wishlists as $wishlist){
            if ($product->getId() === $wishlist->getProducts()->getId() && $wishlist->getUser() === $this->getUser()){
                $wishlistUser = true;
            }
        }

        return $wishlistUser;
    }
}