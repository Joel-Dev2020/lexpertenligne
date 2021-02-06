<?php

namespace App\Managers\Baremes;

use App\Entity\Baremes\Salaires;
use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\Baremes\SalairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class SalairesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var SalairesRepository
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
     * SalairesManagers constructor.
     * @param EntityManagerInterface $em
     * @param SalairesRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        SalairesRepository $repository,
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
     * @param Salaires $salaire
     * @return bool
     */
    public function new(Salaires $salaire){
        if ($salaire instanceof Salaires){
            $this->em->persist($salaire);
            $this->em->flush();
            $action = "Ajout d'un nouveau salaire";
            $content = "Nouveau salaire '{$salaire->getSalairehoraire()} | {$salaire->getSalairemensuel()}' ajouté par " . $this->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Salaires $salaire
     * @return bool
     */
    public function edit(Salaires $salaire){
        if ($salaire instanceof Salaires){
            $this->em->flush();
            $action = "Modification d'un salaire";
            $content = "Salaire '{$salaire->getSalairehoraire()} | {$salaire->getSalairemensuel()}' modifié par {$this->getUser()->getUsername()}";
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Salaires $salaire
     * @return bool
     */
    public function delete(Salaires $salaire){
        if ($salaire instanceof Salaires){
            $action = "Suppression d'un salaire";
            $content = "Salaire '{$salaire->getSalairehoraire()} | {$salaire->getSalairemensuel()}' supprimé par {$this->getUser()->getUsername()}";
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($salaire);
            $this->em->flush();
            return true;
        }
        return false;
    }

    private function getUser(): User
    {
        /**
         * @var $user User
         */
        $user = $this->security->getUser();
        return $user;
    }
}