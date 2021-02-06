<?php

namespace App\Controller\Shop;

use App\Services\Carts\CartsServices;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop", schemes={"https"})
 */
class CartController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;


    /**
     * CartController constructor.
     * @param FlashyNotifier $flashy
     */
    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/cart", name="cart.index", defaults={"title":"Mon panier"})
     * @param $title
     * @param CartsServices $cartsServices
     * @return Response
     */
    public function index($title, CartsServices $cartsServices)
    {
        $products = $cartsServices->getFullCart();
        return $this->render('web/shops/paniers/index.html.twig', [
            'title' => $title,
            'products' =>  $products,
            'totalht' => $cartsServices->getTotalHT(),
            'totaltva' => $cartsServices->getTotalTva(),
            'totalttc' => $cartsServices->getTotalTTC(),
            'current_page' => 'cart',
            'current_global' => 'cart',
        ]);
    }

    /**
     * @Route("/mode/livraison", name="mode.livraison", methods={"POST"})
     * @param Request $request
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function modelivraison(Request $request, SessionInterface $session)
    {
        $modelivraison = intval($request->request->get('modelivraison'));
        $session->get('mode', []);
        $mode = [];
        if ($modelivraison === 0){
            $mode[] = ['key' => $modelivraison, 'mode' => 'Retrait en PDV (Point de vente)'] ;
        }else{
            $mode[] = ['key' => $modelivraison, 'mode' => 'Livraison à domicile'] ;
        }
        $session->set('mode', $mode);
        return $this->redirectToRoute('shop.adresses.index');
    }

    /**
     * @Route("/panier/add/{id}", name="cart.add", methods={"GET","POST"})
     * @param $id
     * @param CartsServices $cartsServices
     * @param Request $request
     * @return Response
     */
    public function add($id, CartsServices $cartsServices, Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $stockAtteint = 0;
            if($cartsServices->add(intval($id)) === true){
                $products = $cartsServices->getFullCart();
                return new JsonResponse([
                    'resultat' => 'OK',
                    'countCart' => $cartsServices->getTotalCount(),
                    'floatCart' => $this->renderView('web/shops/paniers/partials/__float_cart.html.twig', [
                        'items' => $products,
                        'totalht' => $cartsServices->getTotalHT(),
                        'totaltva' => $cartsServices->getTotalTva(),
                        'totalttc' => $cartsServices->getTotalTTC(),
                    ]),
                ], 200);
            }else{
                $stockAtteint = $stockAtteint + 1;
                return  new JsonResponse(['resultat' => 'NONOK', 'error' => $stockAtteint], 301);
            }
        }else{
            $cartsServices->add($id);
            $this->flashy->success('Produit ajouté du panier.');
            return $this->redirectToRoute('cart.index');
        }

    }

    /**
     * @Route("/panier/addwithqty/{id}", name="cart.addwithqtye", methods={"GET","POST"})
     * @param $id
     * @param CartsServices $cartsServices
     * @param Request $request
     * @return Response
     */
    public function addWithQty($id, CartsServices $cartsServices, Request $request)
    {
        $qty = intval($request->request->get('qty'));
        if ($request->isXmlHttpRequest()){
            $stockAtteint = 0;
            if($cartsServices->addWithQty($id, $qty) === true){
                $products = $cartsServices->getFullCart();
                return new JsonResponse([
                    'resultat' => 'OK',
                    'countCart' => $cartsServices->getTotalCount(),
                    'floatCart' => $this->renderView('web/shops/paniers/partials/__float_cart.html.twig', [
                        'items' => $products,
                        'totalht' => $cartsServices->getTotalHT(),
                        'totaltva' => $cartsServices->getTotalTva(),
                        'totalttc' => $cartsServices->getTotalTTC(),
                    ]),
                ], 200);
            }else{
                $stockAtteint = $stockAtteint + 1;
                return  new JsonResponse(['resultat' => 'NONOK', 'error' => $stockAtteint], 301);
            }
        }else{
            $cartsServices->addWithQty($id, $qty);
            $this->flashy->success('Produit ajouté du panier.');
            return $this->redirectToRoute('cart.index');
        }

    }

    /**
     * @Route("/panier/update/{id}", name="cart.update")
     * @param $id
     * @param CartsServices $cartsServices
     * @param Request $request
     * @return Response
     */
    public function update($id, CartsServices $cartsServices, Request $request)
    {
        $qte = (int) $request->query->get('qte');
        if ($request->isXmlHttpRequest()){
            $stockAtteint = 0;
            if($cartsServices->update($id, $qte) === true){
                $products = $cartsServices->getFullCart();
                return new JsonResponse([
                    'resultat' => 'OK',
                    'floatCart' => $this->renderView('web/shops/paniers/partials/__float_cart.html.twig', [
                        'items' => $products,
                        'totalht' => $cartsServices->getTotalHT(),
                        'totaltva' => $cartsServices->getTotalTva(),
                        'totalttc' => $cartsServices->getTotalTTC(),
                    ]),
                    'tableCart' => $this->renderView('web/shops/paniers/partials/__cart_table_recap.html.twig', [
                        'products' => $products,
                        'totalht' => $cartsServices->getTotalHT(),
                        'totaltva' => $cartsServices->getTotalTva(),
                        'totalttc' => $cartsServices->getTotalTTC(),
                    ]),
                ], 200);
            }else{
                $stockAtteint = $stockAtteint + 1;
                return  new JsonResponse(['resultat' => 'NONOK', 'error' => $stockAtteint], 301);
            }
        }else{
            $cartsServices->update($id, $qte);
            $this->flashy->success('Quantité du produit modifiée.');
            return $this->redirectToRoute('cart.index');
        }

    }

    /**
     * @Route("/panier/updated/all", name="cart.update.all", methods={"POST"})
     * @param Request $request
     * @param CartsServices $cartsServices
     * @return Response
     */
    public function updateAllCart(Request $request, CartsServices $cartsServices)
    {
        $qties = $request->request->get('qty');
        $products = $request->request->get('product');
        $datas = array_combine($products, $qties);
        foreach ($datas as $product => $qty){
            $cartsServices->updateAllCart(intval($product), intval($qty));
        }
        $this->flashy->success('Votre panier a été mise à jour.');
        return $this->redirectToRoute('cart.index');
    }

    /**
     * @Route("/panier/remove/{id}", name="cart.remove")
     * @param $id
     * @param CartsServices $cartsServices
     * @param Request $request
     * @return Response
     */
    public function remove($id, CartsServices $cartsServices, Request $request)
    {
        if ($request->isXmlHttpRequest()){
            if($cartsServices->remove($id) === true){
                $products = $cartsServices->getFullCart();
                return new JsonResponse([
                    'resultat' => 'OK',
                    'countCart' => $cartsServices->getTotalCount(),
                    'floatCart' => $this->renderView('web/shops/paniers/partials/__float_cart.html.twig', [
                        'items' => $products,
                        'totalht' => $cartsServices->getTotalHT(),
                        'totaltva' => $cartsServices->getTotalTva(),
                        'totalttc' => $cartsServices->getTotalTTC(),
                    ]),
                    'tableCart' => $this->renderView('web/shops/paniers/partials/__cart_table_recap.html.twig', [
                        'products' => $products,
                        'totalht' => $cartsServices->getTotalHT(),
                        'totaltva' => $cartsServices->getTotalTva(),
                        'totalttc' => $cartsServices->getTotalTTC(),
                    ]),
                ], 200);
            }else{
                return  new JsonResponse(['resultat' => 'NONOK'], 301);
            }
        }else{
            $cartsServices->remove($id);
            $this->flashy->success('Produit supprimé du panier.');
            return $this->redirectToRoute('cart.index');
        }
    }

    /**
     * @Route("/panier/clear", name="cart.clear")
     * @param CartsServices $cartsServices
     * @return Response
     */
    public function clearCart(CartsServices $cartsServices)
    {
        $cartsServices->clearCart();
        $this->flashy->success('Votre panier a été vidé.');
        return $this->redirectToRoute('cart.index');
    }
}
