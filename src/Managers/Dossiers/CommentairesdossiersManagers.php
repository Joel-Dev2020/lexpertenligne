<?php

namespace App\Managers\Dossiers;

use App\Entity\Dossiers\Dossiers;
use App\Entity\Dossiers\Commentairesdossiers;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Dossiers\DossiersRepository;
use App\Repository\Dossiers\CommentairesdossiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CommentairesdossiersManagers
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
     * CommentairesdossiersManagers constructor.
     * @param EntityManagerInterface $em
     * @param CommentairesdossiersRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        CommentairesdossiersRepository $repository,
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
     * @param Commentairesdossiers $commentaire
     * @param Dossiers $dossiers
     * @return bool
     */
    public function new(Commentairesdossiers $commentaire, Dossiers $dossiers){
        if ($commentaire instanceof Commentairesdossiers){
            $commentaire->setUser($this->getUser());
            $commentaire->setEnabled(false);
            $commentaire->setDossiers($dossiers);
            $this->em->persist($commentaire);
            $this->em->flush();
            $action = "Nouveau commentaire ajouté";
            $content = "Nouveau commentaire sur le dossier '{$commentaire->getDossiers()->getName()}' édité par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Commentairesdossiers $commentaire
     * @return bool
     */
    public function edit(Commentairesdossiers $commentaire){
        if ($commentaire instanceof Commentairesdossiers){
            $this->em->flush();
            $action = "Modification d'un commentaire";
            $content = "Commentaire de {$commentaire->getUser()->getNom() } {$commentaire->getUser()->getPrenoms()} sur le dossier '{$commentaire->getDossiers()->getName()}' a été modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Commentairesdossiers $commentaire
     * @return bool
     */
    public function delete(Commentairesdossiers $commentaire){
        if ($commentaire instanceof Commentairesdossiers){
            $action = "Suppression d'un commentaire";
            $content = "Commentaire de {$commentaire->getUser()->getNom() } {$commentaire->getUser()->getPrenoms()} sur le dossier '{$commentaire->getDossiers()->getName()}' a été supprimé par {$this->getUser()->getUsername()}";
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