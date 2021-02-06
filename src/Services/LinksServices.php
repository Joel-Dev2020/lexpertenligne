<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

class LinksServices
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(RouterInterface $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @param string $message
     * @param string $type
     * @param string $generateLink
     * @param array $generateLinkDatas
     * @return bool
     */
    public function setLink(string $message, string $type, string $generateLink, ?array $generateLinkDatas = []) {
        $seeNewAdd = $this->router->generate($generateLink, $generateLinkDatas);
        $link = "<a target='_blank' href='{$seeNewAdd}'>$message</a>";
        $this->container->get('session')->getFlashBag()->add($type, $link);
        return true;
    }
}