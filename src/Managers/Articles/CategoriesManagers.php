<?php

namespace App\Managers\Articles;

use App\Entity\Articles\Categories;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Shop\CategoriesRepository;
use App\Repository\Shop\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CategoriesManagers
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
     * @param CategoriesRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        CategoriesRepository $repository,
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
     * @param Categories $categorie
     * @return bool
     */
    public function new(Categories $categorie){
        if ($categorie instanceof Categories){
            /**
             * @var $user User
             */
            $user = $this->security->getUser();
            $this->em->persist($categorie);
            $this->em->flush();
            $action = "Ajout d'une nouvelle catégorie";
            $content = "Nouvelle catégorie '{$categorie->getName()}' ajoutée par " . $user->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Categories $categorie
     * @return bool
     */
    public function edit(Categories $categorie){
        if ($categorie instanceof Categories){
            $this->em->flush();
            $action = "Modification d'une catégorie";
            $content = "Catégorie '{$categorie->getName()}' modifiée par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Categories $categorie
     * @return bool
     */
    public function delete(Categories $categorie){
        if ($categorie instanceof Categories){
            $action = "Suppression d'une catégorie";
            $content = "Catégorie '{$categorie->getName()}' supprimée par " . $this->security->getUser()->getUsername();
            $this->logs->add($action, $content,"red", "trash");
            $this->em->remove($categorie);
            $this->em->flush();
            return true;
        }
        return false;
    }
}