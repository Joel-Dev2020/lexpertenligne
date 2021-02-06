<?php

namespace App\Services;

use App\Entity\Publicites;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PublicitesServices
{
    private $em;
    private $securityContext;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PublicitesServices constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entitymanager
     */
    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entitymanager
    )
    {
        $this->em = $entitymanager;
        $this->container = $container;
    }

    /**
     * @return bool|object
     */
    public function publicite()
    {
        $idpub = $this->container->getParameter('pub_id');
        /**
         * @var $publicite Publicites
         */
        $publicite = $this->em->getRepository(Publicites::class)->find($idpub);
        if($publicite)
            return $publicite;
        else
            return false;
    }
}