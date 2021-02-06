<?php

namespace App\Managers\Documents;

use App\Entity\Documents\Categoriesdocuments;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Documents\DocumentsRepository;
use App\Repository\Dossiers\CategoriesdossiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CategoriesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var DocumentsRepository
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
     * DocumentsManagers constructor.
     * @param EntityManagerInterface $em
     * @param CategoriesdossiersRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        CategoriesdossiersRepository $repository,
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
     * @param Categoriesdocuments $categorie
     * @return bool
     */
    public function new(Categoriesdocuments $categorie){
        if ($categorie instanceof Categoriesdocuments){
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
     * @param Categoriesdocuments $categorie
     * @return bool
     */
    public function edit(Categoriesdocuments $categorie){
        if ($categorie instanceof Categoriesdocuments){
            $this->em->flush();
            $action = "Modification d'une catégorie";
            $content = "Catégorie '{$categorie->getName()}' modifiée par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Categoriesdocuments $categorie
     * @return bool
     */
    public function delete(Categoriesdocuments $categorie){
        if ($categorie instanceof Categoriesdocuments){
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