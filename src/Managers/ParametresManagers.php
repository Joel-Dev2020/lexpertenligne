<?php

namespace App\Managers;

use App\Entity\Parametres;
use App\Interfaces\LogsInterface;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ParametresManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ParametresRepository
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
     * ParametresManagers constructor.
     * @param EntityManagerInterface $em
     * @param ParametresRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        ParametresRepository $repository,
        LogsInterface $logs,
        Security $security
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->logs = $logs;
        $this->security = $security;
    }

    /**
     * @param Parametres $parametre
     * @return bool
     */
    public function editParametres(Parametres $parametre){
        if ($parametre instanceof Parametres){
            $this->em->flush();
            $action = "Paramètres du site modifiés";
            $content = "Les paramètres du site ont été modifiés par " .  $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }
}