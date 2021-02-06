<?php

namespace App\Services\Carts;

use App\Interfaces\CartInterface;
use App\Repository\ParametresRepository;
use App\Repository\Shop\ProductsRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartsServices implements CartInterface
{

    private $session;
    /**
     * @var ProductsRepository
     */
    private $productsRepository;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ParametresRepository
     */
    private $parametresRepository;

    /**
     * @param SessionInterface $session
     * @param ContainerInterface $container
     * @param ParametresRepository $parametresRepository
     * @param ProductsRepository $productsRepository
     */
    public function __construct(
        SessionInterface $session,
        ContainerInterface $container,
        ParametresRepository $parametresRepository,
        ProductsRepository $productsRepository
    )
    {
        $this->session = $session;
        $this->productsRepository = $productsRepository;
        $this->container = $container;
        $this->parametresRepository = $parametresRepository;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function add(int $id): bool {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])){
            $qte = $panier[$id]['qte'];
            $panier[$id] = [
                'qte' => $qte + 1,
            ];
        }else{
            $panier[$id] = [
                'qte' => 1,
            ];
        }

        $this->session->set('panier', $panier);
        return true;
    }

    /**
     * @param int $id
     * @param int $qty
     * @return bool
     */
    public function addWithQty(int $id, int $qty): bool {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])){
            $panier[$id] = [
                'qte' => $qty,
            ];
        }else{
            $panier[$id] = [
                'qte' => $qty,
            ];
        }

        $this->session->set('panier', $panier);
        return true;
    }

    /**
     * @param int $id
     * @param int $qte
     * @return bool
     */
    public function update(int $id, int $qte): bool {
        $panier = $this->session->get('panier', []);

        if ($qte){
            $panier[$id] =[
                'qte' => $qte,
            ];
        }

        $product = $this->productsRepository->find($id);
        if ($panier[$id]['qte'] > $product->getQuantity() || $qte > $product->getQuantity()){
            return false;
        }

        $this->session->set('panier', $panier);
        return true;
    }

    /**
     * @param int $product_id
     * @param int $qty
     * @return bool
     */
    public function updateAllCart(int $product_id, int $qty): bool {
        $panier = $this->session->get('panier', []);
        if ($product_id && $qty){
            $panier[$product_id] =[
                'qte' => $qty,
            ];
        }
        $this->session->set('panier', $panier);
        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])){
            unset($panier[$id]);
            $this->session->remove('reference');
        }

        $this->session->set('panier', $panier);
        return true;
    }

    /**
     * @return array
     */
    public function getFullCart(): array {
        $panier = $this->session->get('panier', []);
        $panierWithDats = [];
        foreach ($panier as $id => $quantity){
            $panierWithDats[] = [
                'product' => $this->productsRepository->find($id),
                'qte' => $quantity['qte'],
            ];
        }
        return $panierWithDats;
    }

    /**
     * @return float
     */
    public function getTotalHT(): float {
        $total = 0;
        foreach ($this->getFullCart() as $item){
            if ($item['product']->getPricepromo() && $item['product']->getPricepromo() !== 0){
                $total += $item['product']->getPricepromo() * $item['qte'];
            }else{
                $total += $item['product']->getPrice() * $item['qte'];
            }
        }
        return $total;
    }

    /**
     * @return float
     */
    public function getTotalTva(): float {
        $tva = $this->parametresRepository->find($this->container->getParameter('company_id'))->getTva();
        return ($tva * ($this->getTotalHT() / 100));
    }

    /**
     * @return float
     */
    public function getTotalTTC(): float {
        return $this->getTotalTva() + $this->getTotalHT();
    }

    /**
     * @return int
     */
    public function getTotalCount(): int {
        return count($this->getFullCart());
    }

    public function clearCart() {
        $panier = $this->getFullCart();
        if (!empty($panier)){
            unset($panier);
        }
        $this->session->set('panier', []);
    }
}