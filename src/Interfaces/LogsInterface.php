<?php

namespace App\Interfaces;

interface LogsInterface
{
    /**
     * @param string $action
     * @param string $content
     * @param string|null $color
     * @param string|null $icon
     * @return bool
     */
    public function add(string $action, string $content, ?string $color, ?string $icon): bool;

    /**
     * @param string $action
     * @param string $content
     * @param string|null $color
     * @param string|null $icon
     * @return bool
     */
    public function edit(string $action, string $content, ?string $color, ?string $icon): bool;

    /**
     * @param string $action
     * @param string $content
     * @param string|null $color
     * @param string|null $icon
     * @return bool
     */
    public function delete(string $action, string $content, ?string $color, ?string $icon): bool;
}