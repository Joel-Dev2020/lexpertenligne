<?php

namespace App\Managers\Dossiers;

use App\Entity\Dossiers\Categoriesdossiers;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Dossiers\CategoriesdossiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CategoriedossiersManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var CategoriesdossiersRepository
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
     * CategoriesdossiersManagers constructor.
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
     * @param Categoriesdossiers $categorie
     * @return bool
     */
    public function new(Categoriesdossiers $categorie){
        if ($categorie instanceof Categoriesdossiers){
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
     * @param Categoriesdossiers $categorie
     * @return bool
     */
    public function edit(Categoriesdossiers $categorie){
        if ($categorie instanceof Categoriesdossiers){
            $this->em->flush();
            $action = "Modification d'une categorie";
            $content = "Catégorie '{$categorie->getName()}' modifiée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Categoriesdossiers $categorie
     * @return bool
     */
    public function delete(Categoriesdossiers $categorie){
        if ($categorie instanceof Categoriesdossiers){
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