<?php

namespace App\Managers;

use App\Entity\Publicites;
use App\Interfaces\LogsInterface;
use App\Repository\PublicitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PublicitesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var PublicitesRepository
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
     * PublicitesManagers constructor.
     * @param EntityManagerInterface $em
     * @param PublicitesRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        PublicitesRepository $repository,
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
     * @param Publicites $publicite
     * @return bool
     */
    public function edit(Publicites $publicite){
        if ($publicite instanceof Publicites){
            $this->em->flush();
            $action = "Publicités modifiées";
            $content = "Publicités modifiées par " .  $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }
}