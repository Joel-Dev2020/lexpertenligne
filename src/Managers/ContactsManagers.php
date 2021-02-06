<?php

namespace App\Managers;

use App\Entity\Contacts;
use App\Event\ContactEvent;
use App\Interfaces\LogsInterface;
use App\Interfaces\NotificationsInterface;
use App\Repository\ContactsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Security;

class ContactsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ContactsRepository
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
     * ContactsManagers constructor.
     * @param EntityManagerInterface $em
     * @param ContactsRepository $repository
     * @param LogsInterface $logs
     * @param NotificationsInterface $notifications
     * @param EventDispatcherInterface $eventDispatcher
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        ContactsRepository $repository,
        LogsInterface $logs,
        NotificationsInterface $notifications,
        EventDispatcherInterface $eventDispatcher,
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
     * @param Contacts $contact
     * @return bool
     */
    public function new(Contacts $contact){
        if ($contact instanceof Contacts){
            $contact->setActive(true);
            $this->em->persist($contact);
            $this->em->flush();
            $event = new ContactEvent($contact);
            if ($event){
                $this->eventDispatcher->dispatch($event);
            }else{
                return false;
            }
            //On ajoute une notification
            $this->notifications->save('Nouveau message', 'New', $contact, 'check', 'green');
            return true;
        }
        return false;
    }

    /**
     * @param Contacts $contact
     * @return bool
     */
    public function active(Contacts $contact){
        if ($contact instanceof Contacts){
            $contact->setActive(($contact->getActive()) ? false:true);
            $this->em->persist($contact);
            $this->em->flush();
            $action = "Activation/Désactivation d'un message contact";
            $content = "Message contact {$contact->getEmail()} - de {$contact->getNomprenoms()} a été activé/désactivé par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Contacts $contact
     * @return bool
     */
    public function edit(Contacts $contact){
        if ($contact instanceof Contacts){
            $this->em->flush();
            $action = "Modification d'un message contact";
            $content = "Message contact {$contact->getEmail()} - de {$contact->getNomprenoms()} modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Contacts $contact
     * @return bool
     */
    public function delete(Contacts $contact){
        if ($contact instanceof Contacts){
            $action = "Suppression d'un message contact";
            $content = "Message contact {$contact->getEmail()} - de {$contact->getNomprenoms()} supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($contact);
            $this->em->flush();
            return true;
        }
        return false;
    }
}