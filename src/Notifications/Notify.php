<?php

namespace App\Notifications;

use App\Entity\Abonnes;
use App\Entity\Contacts;
use App\Entity\Notifications;
use App\Entity\Shop\Commandes;
use App\Entity\User;
use App\Interfaces\NotificationsInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class Notify implements NotificationsInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $manager, Security $security)
    {
        $this->manager = $manager;
        $this->security = $security;
    }

    /**
     * @param string $titre
     * @param string $action
     * @param object $entity
     * @param string|null $icon
     * @param string $color
     * @return bool
     */
    public function save(string $titre, string $action = 'Nouveau', object $entity, string $icon, string $color): bool
    {
        if (is_object($entity)){
            $notif = new Notifications();
            if ($entity instanceof Contacts){
                $notif->setContacts($entity);
            }
            if ($entity instanceof Abonnes){
                $notif->setAbonnes($entity);
            }
            if ($entity instanceof Commandes){
                $notif->setCommandes($entity);
            }
            $notif->setUser($this->getUser());
            $notif->setTitre($titre);
            $notif->setAction($action);
            $notif->setIcon($icon);
            $notif->setColor($color);
            $notif->setReading(false);
            $this->manager->persist($notif);
            $this->manager->flush();
            return true;
        }
        return false;
    }

    /**
     * @return User
     */
    private function getUser(): ?User {
        /**
         * @var $user User
         */
        $user = $this->security->getUser();
        if ($user){
            return $user;
        }else{
            return $user = null;
        }

    }

    /**
     * @param object $entitie
     * @return bool
     */
    public function view(object $entitie): bool
    {
        if ($entitie->getReading() === true){
            return false;
        }
        $entitie->setIcon('eye');
        $entitie->setColor('#999999');
        $entitie->setReading(true);
        $this->manager->flush();
        return true;
    }
}