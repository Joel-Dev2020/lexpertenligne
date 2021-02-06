<?php

namespace App\Managers\Baremes;

use App\Entity\Baremes\Secteurs;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Baremes\SecteursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class SecteursManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var SecteursRepository
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
     * SecteursManagers constructor.
     * @param EntityManagerInterface $em
     * @param SecteursRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        SecteursRepository $repository,
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
     * @param Secteurs $secteur
     * @return bool
     */
    public function new(Secteurs $secteur){
        if ($secteur instanceof Secteurs){
            $this->em->persist($secteur);
            $this->em->flush();
            $action = "Ajout d'une nouveau secteur";
            $content = "Nouveau secteur '{$secteur->getName()}' ajouté par " . $this->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Secteurs $secteur
     * @return bool
     */
    public function edit(Secteurs $secteur){
        if ($secteur instanceof Secteurs){
            $this->em->flush();
            $action = "Modification d'un secteur";
            $content = "Secteur '{$secteur->getName()}' modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Secteurs $secteur
     * @return bool
     */
    public function delete(Secteurs $secteur){
        if ($secteur instanceof Secteurs){
            $action = "Suppression d'un secteur";
            $content = "Secteur '{$secteur->getName()}' supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($secteur);
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