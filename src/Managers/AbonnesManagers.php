<?php

namespace App\Managers;

use App\Entity\Abonnes;
use App\Event\AbonnesEvent;
use App\Interfaces\LogsInterface;
use App\Interfaces\NotificationsInterface;
use App\Repository\AbonnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Security;

class AbonnesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var AbonnesRepository
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
     * @var NotificationsInterface
     */
    private $notifications;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * AbonnesManagers constructor.
     * @param EntityManagerInterface $em
     * @param AbonnesRepository $repository
     * @param LogsInterface $logs
     * @param EventDispatcherInterface $eventDispatcher
     * @param NotificationsInterface $notifications
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        AbonnesRepository $repository,
        LogsInterface $logs,
        EventDispatcherInterface $eventDispatcher,
        NotificationsInterface $notifications,
        Security $security
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->logs = $logs;
        $this->security = $security;
        $this->notifications = $notifications;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Abonnes $abonne
     * @return bool
     */
    public function new(Abonnes $abonne){
        if ($abonne instanceof Abonnes){
            $abonne->setActive(true);
            $this->em->persist($abonne);
            $this->em->flush();
            //On ajoute une notification
            $event = new AbonnesEvent($abonne);
            $this->eventDispatcher->dispatch($event);
            $this->notifications->save('Nouvel abonné', 'New', $abonne, 'check', 'green');
            return true;
        }
        return false;
    }

    /**
     * @param Abonnes $abonne
     * @return bool
     */
    public function active(Abonnes $abonne){
        if (Abonnes instanceof $abonne){
            $abonne->setActive(($abonne->getActive()) ? false:true);
            $this->em->persist($abonne);
            $this->em->flush();
            $action = "Activation/Désactivation d'un abonné";
            $content = "Abonné {$abonne->getEmail()} a été activé/désactivé par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Abonnes $abonne
     * @return bool
     */
    public function edit(Abonnes $abonne){
        if (Abonnes instanceof $abonne){
            $this->em->flush();
            $action = "Modification d'un abonné";
            $content = "Abonné '{$abonne->getEmail()}' modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Abonnes $abonne
     * @return bool
     */
    public function delete(Abonnes $abonne){
        if (Abonnes instanceof $abonne){
            $action = "Suppression d'un abonné";
            $content = "Abonné '{$abonne->getEmail()}' supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($abonne);
            $this->em->flush();
            return true;
        }
        return false;
    }
}