<?php

namespace App\Interfaces;

interface NotificationsInterface
{
    /**
     * @param string $titre
     * @param string $action
     * @param object $entity
     * @param string|null $icon
     * @param string|null $color
     * @return bool
     */
    public function save(string $titre, string $action = 'Nouveau', object $entity, string $icon, string $color): bool;

    public function view(object $entitie): bool;
}