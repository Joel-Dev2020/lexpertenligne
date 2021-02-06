<?php

namespace App\Managers\Formations;

use App\Entity\Formations\Formations;
use App\Entity\Formations\Mediasformations;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Formations\FormationsRepository;
use App\Repository\Formations\MediasformationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MediasformationsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var FormationsRepository
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
     * MediasformationsManagers constructor.
     * @param EntityManagerInterface $em
     * @param MediasformationsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        MediasformationsRepository $repository,
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
     * @param Mediasformations $mediasformation
     * @param Formations $formations
     * @return bool
     */
    public function new(Mediasformations $mediasformation, Formations $formations){
        if ($mediasformation instanceof Mediasformations){
            $mediasformation->setFormations($formations);
            $this->em->persist($mediasformation);
            $this->em->flush();
            $action = "Ajout d'une nouvelle photo";
            $content = "Nouvelle  photo '{$mediasformation->getName()}' ajoutée par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Mediasformations $mediasformation
     * @param Formations $formation
     * @return bool
     */
    public function edit(Mediasformations $mediasformation, Formations $formation){
        if ($mediasformation instanceof Mediasformations){
            $this->em->flush();
            $action = "Modification d'une photo";
            $content = "Photo '{$mediasformation->getName()}' modifiée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Mediasformations $mediasformation
     * @param Formations $formation
     * @return bool
     */
    public function delete(Mediasformations $mediasformation, Formations $formation){
        if ($mediasformation instanceof Mediasformations){
            $mediasformation->setFormations($formation);
            $action = "Suppression d'une photo";
            $content = "Photo '{$mediasformation->getName()}' supprimée par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($mediasformation);
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