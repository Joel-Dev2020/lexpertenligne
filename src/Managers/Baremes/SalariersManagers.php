<?php

namespace App\Managers\Baremes;

use App\Entity\Baremes\Salariers;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Baremes\SalariersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class SalariersManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var SalariersRepository
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
     * SalariersManagers constructor.
     * @param EntityManagerInterface $em
     * @param SalariersRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        SalariersRepository $repository,
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
     * @param Salariers $salarier
     * @return bool
     */
    public function new(Salariers $salarier){
        if ($salarier instanceof Salariers){
            $this->em->persist($salarier);
            $this->em->flush();
            $action = "Ajout d'un nouveau salarier";
            $content = "Nouveau salarier '{$salarier->getName()}' ajouté par " . $this->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Salariers $salarier
     * @return bool
     */
    public function edit(Salariers $salarier){
        if ($salarier instanceof Salariers){
            $this->em->flush();
            $action = "Modification d'un salarier";
            $content = "Salarier '{$salarier->getName()}' modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Salariers $salarier
     * @return bool
     */
    public function delete(Salariers $salarier){
        if ($salarier instanceof Salariers){
            $action = "Suppression d'un salarier";
            $content = "Salarier '{$salarier->getName()}' supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($salarier);
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