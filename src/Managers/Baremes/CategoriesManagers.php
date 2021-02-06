<?php

namespace App\Managers\Baremes;

use App\Entity\Baremes\Categories;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Baremes\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CategoriesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var CategoriesRepository
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
     * CategoriesManagers constructor.
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
            $this->em->persist($categorie);
            $this->em->flush();
            $action = "Ajout d'unee nouvelle categorie";
            $content = "Nouvelle categorie '{$categorie->getName()}' ajoutée par " . $this->getUser()->getUsername();
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
            $action = "Modification d'une categorie";
            $content = "Categorie '{$categorie->getName()}' modifiée par {$this->getUser()->getUsername()}";
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
            $action = "Suppression d'une categorie";
            $content = "Categorie '{$categorie->getName()}' supprimée par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($categorie);
            $this->em->flush();
            return true;
        }
        return false;
    }

    private function getUser(): User
    {
        /**
         * @var $user User
         */
        $user = $this->security->getUser();
        return $user;
    }
}