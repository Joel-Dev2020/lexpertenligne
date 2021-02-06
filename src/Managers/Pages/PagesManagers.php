<?php

namespace App\Managers\Pages;

use App\Entity\Pages\Pages;
use App\Interfaces\LogsInterface;
use App\Repository\Pages\PagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PagesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var PagesRepository
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
     * PagesManagers constructor.
     * @param EntityManagerInterface $em
     * @param PagesRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        PagesRepository $repository,
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
     * @param Pages $page
     * @return bool
     */
    public function new(Pages $page){
        if ($page instanceof Pages){
            $this->em->persist($page);
            $this->em->flush();
            $action = "Ajout d'une nouvelle page";
            $content = "Nouvelle  page '{$page->getName()}' ajoutée par " . $this->security->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Pages $page
     * @return bool
     */
    public function active(Pages $page){
        if ($page instanceof Pages){
            $page->setOnline(($page->getOnline()) ? false:true);
            $this->em->persist($page);
            $this->em->flush();
            $action = "Activation/Désactivation du post";
            $content = "Le post {$page->getName()} a été activé/désactivé par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Pages $page
     * @return bool
     */
    public function edit(Pages $page){
        if ($page instanceof Pages){
            $this->em->flush();
            $action = "Modification d'une page";
            $content = "Page '{$page->getName()}' modifiée par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Pages $page
     * @return bool
     */
    public function delete(Pages $page){
        if ($page instanceof Pages){
            $action = "Suppression d'une page";
            $content = "Page '{$page->getName()}' supprimée par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($page);
            $this->em->flush();
            return true;
        }
        return false;
    }
}