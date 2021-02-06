<?php

namespace App\Managers\Formations;

use App\Entity\Formations\Formations;
use App\Entity\Formations\Programformations;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Formations\FormationsRepository;
use App\Repository\Formations\ProgramformationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ProgramformationsManagers
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
     * ProgramformationsManagers constructor.
     * @param EntityManagerInterface $em
     * @param ProgramformationsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        ProgramformationsRepository $repository,
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
     * @param Programformations $programformation
     * @param Formations $formations
     * @return bool
     */
    public function new(Programformations $programformation, Formations $formations){
        if ($programformation instanceof Programformations){
            $programformation->setFormations($formations);
            $this->em->persist($programformation);
            $this->em->flush();
            $action = "Ajout d'un nouveau programme";
            $content = "Nouveau programme '{$programformation->getName()}' ajouté par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Programformations $programformation
     * @param Formations $formation
     * @return bool
     */
    public function edit(Programformations $programformation, Formations $formation){
        if ($programformation instanceof Programformations){
            $this->em->flush();
            $action = "Modification d'un programme";
            $content = "Programme '{$programformation->getName()}' modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Programformations $programformation
     * @param Formations $formation
     * @return bool
     */
    public function delete(Programformations $programformation, Formations $formation){
        if ($programformation instanceof Programformations){
            $programformation->setFormations($formation);
            $action = "Suppression d'un programme";
            $content = "Programme '{$programformation->getName()}' supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($programformation);
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