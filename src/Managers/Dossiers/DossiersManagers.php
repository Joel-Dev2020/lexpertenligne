<?php

namespace App\Managers\Dossiers;

use App\Entity\Dossiers\Dossiers;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Dossiers\DossiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class DossiersManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var DossiersRepository
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
     * DossiersManagers constructor.
     * @param EntityManagerInterface $em
     * @param DossiersRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        DossiersRepository $repository,
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
     * @param Dossiers $dossier
     * @return bool
     */
    public function new(Dossiers $dossier){
        if ($dossier instanceof Dossiers){
            $dossier->setUser($this->getUser());
            $this->em->persist($dossier);
            $this->em->flush();
            $action = "Ajout d'une nouveau dossier";
            $content = "Nouveau  dossier '{$dossier->getName()}' ajouté par " . $this->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Dossiers $dossier
     * @return bool
     */
    public function active(Dossiers $dossier){
        if ($dossier instanceof Dossiers){
            $dossier->setOnline(($dossier->getOnline()) ? false:true);
            $this->em->persist($dossier);
            $this->em->flush();
            $action = "Activation/Désactivation du dossier";
            $content = "Le dossier {$dossier->getName()} a été activé/désactivé par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Dossiers $dossier
     * @return bool
     */
    public function edit(Dossiers $dossier){
        if ($dossier instanceof Dossiers){
            $this->em->flush();
            $action = "Modification d'un dossier";
            $content = "Dossier '{$dossier->getName()}' modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Dossiers $dossier
     * @return bool
     */
    public function delete(Dossiers $dossier){
        if ($dossier instanceof Dossiers){
            $action = "Suppression d'un dossier";
            $content = "Dossier '{$dossier->getName()}' supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($dossier);
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