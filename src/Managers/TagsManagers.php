<?php

namespace App\Managers;

use App\Entity\Tags;
use App\Interfaces\LogsInterface;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TagsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var TagsRepository
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
     * TagsManagers constructor.
     * @param EntityManagerInterface $em
     * @param TagsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        TagsRepository $repository,
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
     * @param Tags $tag
     * @return bool
     */
    public function new(Tags $tag){
        if ($tag instanceof Tags){
            $this->em->persist($tag);
            $this->em->flush();
            $action = "Ajout d'un nouveau tag";
            $content = "Nouveau tag '{$tag->getName()}' ajouté par " . $this->security->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Tags $tag
     * @return bool
     */
    public function edit(Tags $tag){
        if ($tag instanceof Tags){
            $this->em->flush();
            $action = "Modification du tag";
            $content = "Tag '{$tag->getName()}' modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Tags $tag
     * @return bool
     */
    public function delete(Tags $tag){
        if ($tag instanceof Tags){
            $action = "Suppression du tag";
            $content = "Tag '{$tag->getName()}' supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($tag);
            $this->em->flush();
            return true;
        }
        return false;
    }
}