<?php

namespace App\Managers\Formations;

use App\Entity\Formations\Formations;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Formations\FormationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class FormationsManagers
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
     * FormationsManagers constructor.
     * @param EntityManagerInterface $em
     * @param FormationsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        FormationsRepository $repository,
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
     * @param Formations $formation
     * @return bool
     */
    public function new(Formations $formation){
        if ($formation instanceof Formations){
            $formation->setUser($this->getUser());
            $this->em->persist($formation);
            $this->em->flush();
            $action = "Ajout d'une nouvelle formation";
            $content = "Nouvelle formation '{$formation->getName()}' ajoutée par " . $this->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Formations $formation
     * @return bool
     */
    public function active(Formations $formation){
        if ($formation instanceof Formations){
            $formation->setOnline(($formation->getOnline()) ? false:true);
            $this->em->persist($formation);
            $this->em->flush();
            $action = "Activation/Désactivation du formation";
            $content = "Le formation {$formation->getName()} a été activée/désactivée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Formations $formation
     * @return bool
     */
    public function edit(Formations $formation){
        if ($formation instanceof Formations){
            $this->em->flush();
            $action = "Modification d'une formation";
            $content = "Formation '{$formation->getName()}' modifiée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Formations $formation
     * @return bool
     */
    public function delete(Formations $formation){
        $action = "Suppression d'une formation";
        $content = "Formation '{$formation->getName()}' supprimée par {$this->getUser()->getUsername()}";
        $this->logs->delete($action, $content,"red", "trash");
        $this->em->remove($formation);
        $this->em->flush();
        return true;
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