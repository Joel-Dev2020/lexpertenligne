<?php

namespace App\Services;

use App\Entity\Logs;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class LogsService implements LogsInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var Security
     */
    private $security;

    /**
     * LogsServices constructor.
     * @param EntityManagerInterface $manager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $manager, Security $security)
    {
        $this->manager = $manager;
        $this->security = $security;
    }

    /**
     * @param string $action
     * @param string $content
     * @param string|null $color
     * @param string|null $icon
     * @return bool
     */
    public function add(string $action, string $content, ?string $color = 'green', ?string $icon = 'check'): bool
    {
        return $this->newlog($action, $content, $color, $icon);
    }

    /**
     * @param string $action
     * @param string $content
     * @param string|null $color
     * @param string|null $icon
     * @return bool
     */
    public function edit(string $action, string $content, ?string $color = 'orange', ?string $icon = 'edit'): bool
    {
        return $this->newlog($action, $content, $color, $icon);
    }

    /**
     * @param string $action
     * @param string $content
     * @param string|null $color
     * @param string|null $icon
     * @return bool
     */
    public function delete(string $action, string $content, ?string $color = 'red', ?string $icon = 'trash'): bool
    {
        return $this->newlog($action, $content, $color, $icon);
    }

    /**
     * @param string $content
     * @param string $action
     * @param string $color
     * @param string $icon
     * @return bool
     */
    private function newlog(string $content, string $action, ?string $color, ?string $icon): bool {
        $log = new Logs();
        $log->setUser($this->getUser());
        $log->setContent($content);
        $log->setAction($action);
        $log->setColor($color);
        $log->setIcon($icon);
        $this->manager->persist($log);
        $this->manager->flush();
        return true;
    }

    /**
     * @return User
     */
    private function getUser(): User {
        /**
         * @var $user User
         */
        $user = $this->security->getUser();
        return $user;
    }
}