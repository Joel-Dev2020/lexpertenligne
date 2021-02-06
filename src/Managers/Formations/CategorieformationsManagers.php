<?php

namespace App\Managers\Formations;

use App\Entity\Formations\Categoriesformations;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Formations\CategoriesformationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CategorieformationsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var CategoriesformationsRepository
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
     * CategoriesformationsManagers constructor.
     * @param EntityManagerInterface $em
     * @param CategoriesformationsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        CategoriesformationsRepository $repository,
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
     * @param Categoriesformations $categorie
     * @return bool
     */
    public function new(Categoriesformations $categorie){
        if ($categorie instanceof Categoriesformations){
            $this->em->persist($categorie);
            $this->em->flush();
            $action = "Ajout d'une nouvelle categorie";
            $content = "Nouvelle  categorie '{$categorie->getName()}' ajoutée par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Categoriesformations $categorie
     * @return bool
     */
    public function edit(Categoriesformations $categorie){
        if ($categorie instanceof Categoriesformations){
            $this->em->flush();
            $action = "Modification d'une categorie";
            $content = "Catégorie '{$categorie->getName()}' modifiée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Categoriesformations $categorie
     * @return bool
     */
    public function delete(Categoriesformations $categorie){
        if ($categorie instanceof Categoriesformations){
            $action = "Suppression d'une categorie";
            $content = "Catégorie '{$categorie->getName()}' supprimée par {$this->getUser()->getUsername()}";
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