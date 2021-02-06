<?php

namespace App\Interfaces;

interface CartInterface
{
    /**
     * @param int $id
     * @return bool
     */
    public function add(int $id): bool ;

    /**
     * @param int $id
     * @param int $qty
     * @return bool
     */
    public function addWithQty(int $id, int $qty): bool ;

    /**
     * @param int $id
     * @param int $qte
     * @return bool
     */
    public function update(int $id, int $qte): bool ;

    /**
     * @param int $product_id
     * @param int $qty
     * @return bool
     */
    public function updateAllCart(int $product_id, int $qty): bool ;

    /**
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool ;

    /**
     * @return array|null
     */
    public function getFullCart(): ?array ;

    /**
     * @return float
     */
    public function getTotalHT(): float ;

    /**
     * @return float
     */
    public function getTotalTva(): float ;

    /**
     * @return float
     */
    public function getTotalTTC(): float ;

    /**
     * @return int
     */
    public function getTotalCount(): int ;

    /**
     * @return mixed
     */
    public function clearCart() ;
}