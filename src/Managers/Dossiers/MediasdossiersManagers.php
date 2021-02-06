<?php

namespace App\Managers\Dossiers;

use App\Entity\Dossiers\Dossiers;
use App\Entity\Dossiers\Mediasdossiers;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Dossiers\DossiersRepository;
use App\Repository\Dossiers\MediasdossiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MediasdossiersManagers
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
     * MediasdossiersManagers constructor.
     * @param EntityManagerInterface $em
     * @param MediasdossiersRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        MediasdossiersRepository $repository,
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
     * @param Mediasdossiers $mediasdossier
     * @param Dossiers $dossiers
     * @return bool
     */
    public function new(Mediasdossiers $mediasdossier, Dossiers $dossiers){
        if ($mediasdossier instanceof Mediasdossiers){
            $mediasdossier->setDossiers($dossiers);
            $this->em->persist($mediasdossier);
            $this->em->flush();
            $action = "Ajout d'une nouvelle photo";
            $content = "Nouvelle  photo '{$mediasdossier->getName()}' ajoutée par {$this->getUser()->getUsername()}";
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Mediasdossiers $mediasdossier
     * @param Dossiers $dossier
     * @return bool
     */
    public function edit(Mediasdossiers $mediasdossier, Dossiers $dossier){
        if ($mediasdossier instanceof Mediasdossiers){
            $this->em->flush();
            $action = "Modification d'une photo";
            $content = "Photo '{$mediasdossier->getName()}' modifiée par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Mediasdossiers $mediasdossier
     * @param Dossiers $dossier
     * @return bool
     */
    public function delete(Mediasdossiers $mediasdossier, Dossiers $dossier){
        if ($mediasdossier instanceof Mediasdossiers){
            $mediasdossier->setDossiers($dossier);
            $action = "Suppression d'une photo";
            $content = "Photo '{$mediasdossier->getName()}' supprimée par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($mediasdossier);
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