<?php

namespace App\Managers\Formations;

use App\Entity\Formations\Formations;
use App\Entity\Formations\Commentairesformations;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Formations\FormationsRepository;
use App\Repository\Formations\CommentairesformationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CommentairesformationsManagers
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
     * CommentairesformationsManagers constructor.
     * @param EntityManagerInterface $em
     * @param CommentairesformationsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        CommentairesformationsRepository $repository,
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
     * @param Commentairesformations $commentaire
     * @param Formations $formations
     * @return bool
     */
    public function new(Commentairesformations $commentaire, Formations $formations){
        if ($commentaire instanceof Commentairesformations){
            $commentaire->setUser($this->getUser());
            $commentaire->setEnabled(false);
            $commentaire->setFormations($formations);
            $this->em->persist($commentaire);
            $this->em->flush();
            $action = "Nouveau commentaire ajouté";
            $content = "Nouveau commentaire sur le formation '{$commentaire->getFormations()->getName()}' édité par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Commentairesformations $commentaire
     * @return bool
     */
    public function edit(Commentairesformations $commentaire){
        if ($commentaire instanceof Commentairesformations){
            $this->em->flush();
            $action = "Modification d'un commentaire";
            $content = "Commentaire de {$commentaire->getUser()->getNom() } {$commentaire->getUser()->getPrenoms()} sur le formation '{$commentaire->getFormations()->getName()}' a été modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Commentairesformations $commentaire
     * @return bool
     */
    public function delete(Commentairesformations $commentaire){
        if ($commentaire instanceof Commentairesformations){
            $action = "Suppression d'un commentaire";
            $content = "Commentaire de {$commentaire->getUser()->getNom() } {$commentaire->getUser()->getPrenoms()} sur le formation '{$commentaire->getFormations()->getName()}' a été supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($commentaire);
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