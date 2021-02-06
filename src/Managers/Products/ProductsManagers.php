<?php

namespace App\Managers\Products;

use App\Entity\Shop\Products;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Shop\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ProductsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ProductsRepository
     */
    private $repository;
    /**
     * @var LogsInterface
     */
    private $logs;
    /**
     * @var Security
     */
    private $security;

    /**
     * ProductsManagers constructor.
     * @param EntityManagerInterface $em
     * @param ProductsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        ProductsRepository $repository,
        LogsInterface $logs,
        Security $security
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->logs = $logs;
        $this->security = $security;
    }

    /**
     * @param Products $product
     * @return bool
     */
    public function register(Products $product){
        if ($product instanceof Products){
            /**
             * @var $user User
             */
            $user = $this->security->getUser();
            $product->setUser($user);
            $product->setVues(0);
            $this->em->persist($product);
            $this->em->flush();
            $action = "Ajout d'un nouveau produit";
            $content = "Nouveau produit '{$product->getName()}' ajouté par " . $user->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Products $product
     * @return bool
     */
    public function edit(Products $product){
        if ($product instanceof Products){
            $this->em->flush();
            $action = "Modification d'un produit";
            $content = "Produit '{$product->getName()}' modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Products $product
     * @return bool
     */
    public function delete(Products $product){
        if ($product instanceof Products){
            $action = "Suppression d'un produit";
            $content = "Produit '{$product->getName()}' supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->add($action, $content,"red", "trash");
            $this->em->remove($product);
            $this->em->flush();
            return true;
        }
        return false;
    }
}