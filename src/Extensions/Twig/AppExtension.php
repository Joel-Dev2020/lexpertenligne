<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('set_active_route', [$this, 'setActiveRoute']),
            new TwigFunction('set_active_route_global', [$this, 'setActiveRouteGlobal']),
            new TwigFunction('set_active_select', [$this, 'setActiveSelect']),
            new TwigFunction('pluralize', [$this, 'setPluralize']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('ucfirst', [$this, 'getUcfirst']),
        ];
    }

    /**
     * @param string|null $currentPage
     * @param string $route
     * @param string|null $activedClass
     * @return string
     */
    public function setActiveRoute(?string $currentPage, string $route, ?string $activedClass = 'active'): string
    {
        if (isset($currentPage) && !empty($currentPage) && $currentPage === $route){
            return $activedClass;
        }else{
            return '';
        }
    }

    /**
     * @param string|null $currentPage
     * @param string $route
     * @param string|null $activedClass
     * @return string
     */
    public function setActiveRouteGlobal(?string $currentPage, string $route, ?string $activedClass = 'open active'): string
    {
        if (isset($currentPage) && !empty($currentPage) && $currentPage === $route){
            return $activedClass;
        }else{
            return '';
        }
    }

    /**
     * @param string|null $currentPage
     * @param string $route
     * @param string|null $activedClass
     * @return string
     */
    public function setActiveSelect(?string $currentPage, string $route, ?string $activedClass = 'selectedActive'): string
    {
        if (isset($currentPage) && !empty($currentPage) && $currentPage === $route){
            return $activedClass;
        }else{
            return '';
        }
    }


    public function getUcfirst(string $string): string
    {
        return ucfirst(strtolower($string));
    }

    public function setPluralize(int $count, string $singular, ?string $plural = null): string
    {
        $plural = $plural ?? $singular .'s';
        $str = $count === 1 ? $singular : $plural;
        return "$count $str";
    }
}
