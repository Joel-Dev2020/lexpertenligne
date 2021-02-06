<?php

namespace App\Managers\Blogs;

use App\Entity\Blogs\Categoriesblogs;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Blogs\CategoriesblogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CategorieblogsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var CategoriesblogsRepository
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
     * CategoriesblogsManagers constructor.
     * @param EntityManagerInterface $em
     * @param CategoriesblogsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        CategoriesblogsRepository $repository,
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
     * @param Categoriesblogs $categorie
     * @return bool
     */
    public function new(Categoriesblogs $categorie){
        if ($categorie instanceof Categoriesblogs){
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
     * @param Categoriesblogs $categorie
     * @return bool
     */
    public function edit(Categoriesblogs $categorie){
        if ($categorie instanceof Categoriesblogs){
            $this->em->flush();
            $action = "Modification d'une categorie";
            $content = "Catégorie '{$categorie->getName()}' modifiée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Categoriesblogs $categorie
     * @return bool
     */
    public function delete(Categoriesblogs $categorie){
        if ($categorie instanceof Categoriesblogs){
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