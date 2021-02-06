<?php

namespace App\Managers\Blogs;

use App\Entity\Blogs\Blogs;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Blogs\BlogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class BlogsManagers
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
     * BlogsManagers constructor.
     * @param EntityManagerInterface $em
     * @param BlogsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        BlogsRepository $repository,
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
     * @param Blogs $blog
     * @return bool
     */
    public function new(Blogs $blog){
        if ($blog instanceof Blogs){
            $blog->setUser($this->getUser());
            $this->em->persist($blog);
            $this->em->flush();
            $action = "Ajout d'une nouveau blog";
            $content = "Nouveau  blog '{$blog->getName()}' ajouté par " . $this->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Blogs $blog
     * @return bool
     */
    public function active(Blogs $blog){
        if ($blog instanceof Blogs){
            $blog->setOnline(($blog->getOnline()) ? false:true);
            $this->em->persist($blog);
            $this->em->flush();
            $action = "Activation/Désactivation du blog";
            $content = "Le blog {$blog->getName()} a été activé/désactivé par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Blogs $blog
     * @return bool
     */
    public function edit(Blogs $blog){
        if ($blog instanceof Blogs){
            $this->em->flush();
            $action = "Modification d'un blog";
            $content = "Blog '{$blog->getName()}' modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Blogs $blog
     * @return bool
     */
    public function delete(Blogs $blog){
        if ($blog instanceof Blogs){
            $action = "Suppression d'un blog";
            $content = "Blog '{$blog->getName()}' supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($blog);
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