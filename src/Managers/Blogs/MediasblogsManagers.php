<?php

namespace App\Managers\Blogs;

use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\Mediasblogs;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\MediasblogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MediasblogsManagers
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
     * MediasblogsManagers constructor.
     * @param EntityManagerInterface $em
     * @param MediasblogsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        MediasblogsRepository $repository,
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
     * @param Mediasblogs $mediasblog
     * @param Blogs $blogs
     * @return bool
     */
    public function new(Mediasblogs $mediasblog, Blogs $blogs){
        if ($mediasblog instanceof Mediasblogs){
            $mediasblog->setBlogs($blogs);
            $this->em->persist($mediasblog);
            $this->em->flush();
            $action = "Ajout d'une nouvelle photo";
            $content = "Nouvelle  photo '{$mediasblog->getName()}' ajoutée par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Mediasblogs $mediasblog
     * @param Blogs $blog
     * @return bool
     */
    public function edit(Mediasblogs $mediasblog, Blogs $blog){
        if ($mediasblog instanceof Mediasblogs){
            $this->em->flush();
            $action = "Modification d'une photo";
            $content = "Photo '{$mediasblog->getName()}' modifiée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Mediasblogs $mediasblog
     * @param Blogs $blog
     * @return bool
     */
    public function delete(Mediasblogs $mediasblog, Blogs $blog){
        if ($mediasblog instanceof Mediasblogs){
            $mediasblog->setBlogs($blog);
            $action = "Suppression d'une photo";
            $content = "Photo '{$mediasblog->getName()}' supprimée par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($mediasblog);
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