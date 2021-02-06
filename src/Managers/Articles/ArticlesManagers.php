<?php

namespace App\Managers\Articles;

use App\Entity\Articles\Articles;
use App\Interfaces\LogsInterface;
use App\Repository\Articles\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ArticlesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ArticlesRepository
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
     * ArticlesManagers constructor.
     * @param EntityManagerInterface $em
     * @param ArticlesRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        ArticlesRepository $repository,
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
     * @param Articles $article
     * @return bool
     */
    public function new(Articles $article){
        if ($article instanceof Articles){
            $this->em->persist($article);
            $this->em->flush();
            $action = "Ajout d'un nouvel article";
            $content = "Nouvel article '{$article->getName()}' ajouté par " . $this->security->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Articles $article
     * @return bool
     */
    public function edit(Articles $article){
        if ($article instanceof Articles){
            $this->em->flush();
            $action = "Modification d'un article";
            $content = "Article '{$article->getName()}' modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Articles $article
     * @return bool
     */
    public function delete(Articles $article){
        if ($article instanceof Articles){
            $action = "Suppression d'un article";
            $content = "Article '{$article->getName()}' supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($article);
            $this->em->flush();
            return true;
        }
        return false;
    }
}