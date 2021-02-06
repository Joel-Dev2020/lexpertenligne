<?php

namespace App\Controller\Shop;

use App\Data\SearchData;
use App\Entity\Shop\Products;
use App\Entity\Shop\Wishlists;
use App\Form\Shop\SearchFormType;
use App\Repository\Shop\ProductsRepository;
use App\Repository\Shop\WishlistsRepository;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search", schemes={"https"})
 */
class SearchController extends AbstractController
{

    /**
     * @var WishlistsRepository
     */
    private $wishlistsRepository;

    public function __construct(WishlistsRepository $wishlistsRepository)
    {
        $this->wishlistsRepository = $wishlistsRepository;
    }

    /**
     * @Route("/shop/product/query", name="search.home", schemes={"https"})
     * @param $title
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @return Response
     */
    public function index($title,Request $request, ProductsRepository $productsRepository)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);
        $products = $productsRepository->findSearch($data);
        return $this->render('web/shops/products/index.html.twig', [
            'title' => 'Recherche dans E-boutique',
            'search' => $data->q,
            'products' => $products,
            'form' => $form->createView(),
            'current_page' => 'search',
            'current_global' => 'search'
        ]);
    }

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
