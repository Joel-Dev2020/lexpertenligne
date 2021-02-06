<?php

namespace App\Services;

use App\Entity\Parametres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ParametresServices
{
    private $em;
    private $securityContext;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * getReference constructor.
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
    public function structure()
    {
        $idstructure = $this->container->getParameter('company_id');
        /**
         * @var $structure Parametres
         */
        $structure = $this->em->getRepository(Parametres::class)->find($idstructure);
        if($structure)
            return $structure;
        else
            return false;
    }
}