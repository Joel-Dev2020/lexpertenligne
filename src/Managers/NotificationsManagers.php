<?php

namespace App\Managers;

use App\Entity\Notifications;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var NotificationsRepository
     */
    private $repository;

    /**
     * NotificationsManagers constructor.
     * @param EntityManagerInterface $em
     * @param NotificationsRepository $repository
     */
    public function __construct(
        EntityManagerInterface $em,
        NotificationsRepository $repository
    )
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param Notifications $notification
     * @return bool
     */
    public function delete(Notifications $notification){
        if ($notification instanceof Notifications){
            $this->em->remove($notification);
            $this->em->flush();
            return true;
        }
        return false;
    }
}