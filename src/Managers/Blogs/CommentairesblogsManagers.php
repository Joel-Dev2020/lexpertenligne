<?php

namespace App\Managers\Blogs;

use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\Commentairesblogs;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\CommentairesblogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CommentairesblogsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var BlogsRepository
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
     * CommentairesblogsManagers constructor.
     * @param EntityManagerInterface $em
     * @param CommentairesblogsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        CommentairesblogsRepository $repository,
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
     * @param Blogs $blogs
     * @return bool
     */
    public function getComment(Blogs $blogs){
        if ($blogs instanceof Blogs){
            return true;
        }
        return false;
    }

    /**
     * @param Commentairesblogs $commentaire
     * @param Blogs $blogs
     * @return bool
     */
    public function new(Commentairesblogs $commentaire, Blogs $blogs){
        if ($commentaire instanceof Commentairesblogs){
            $commentaire->setUser($this->getUser());
            $commentaire->setEnabled(false);
            $commentaire->setBlogs($blogs);
            $this->em->persist($commentaire);
            $this->em->flush();
            $action = "Nouveau commentaire ajouté";
            $content = "Nouveau commentaire sur le blog '{$commentaire->getBlogs()->getName()}' édité par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Commentairesblogs $commentaire
     * @return bool
     */
    public function edit(Commentairesblogs $commentaire){
        if ($commentaire instanceof Commentairesblogs){
            $this->em->flush();
            $action = "Modification d'un commentaire";
            $content = "Commentaire de {$commentaire->getUser()->getNom() } {$commentaire->getUser()->getPrenoms()} sur le blog '{$commentaire->getBlogs()->getName()}' a été modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Commentairesblogs $commentaire
     * @return bool
     */
    public function delete(Commentairesblogs $commentaire){
        if ($commentaire instanceof Commentairesblogs){
            $action = "Suppression d'un commentaire";
            $content = "Commentaire de {$commentaire->getUser()->getNom() } {$commentaire->getUser()->getPrenoms()} sur le blog '{$commentaire->getBlogs()->getName()}' a été supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($commentaire);
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