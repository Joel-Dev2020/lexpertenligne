<?php

namespace App\Managers\Dictionnaires;

use App\Entity\Dictionnaires\Dictionnaires;
use App\Interfaces\LogsInterface;
use App\Repository\Dictionnaires\DictionnairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class DictionnairesManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var DictionnairesRepository
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
     * DictionnairesManagers constructor.
     * @param EntityManagerInterface $em
     * @param DictionnairesRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        DictionnairesRepository $repository,
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
     * @param Dictionnaires $dictionnaire
     * @return bool
     */
    public function new(Dictionnaires $dictionnaire){
        if ($dictionnaire instanceof Dictionnaires){
            $this->em->persist($dictionnaire);
            $this->em->flush();
            $action = "Ajout d'un nouveau lexique";
            $content = "Nouveau lexique'{$dictionnaire->getLexique()}' ajouté par " . $this->security->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Dictionnaires $dictionnaire
     * @return bool
     */
    public function edit(Dictionnaires $dictionnaire){
        if ($dictionnaire instanceof Dictionnaires){
            $this->em->flush();
            $action = "Modification du lexique";
            $content = "Lexique '{$dictionnaire->getLexique()}' modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Dictionnaires $dictionnaire
     * @return bool
     */
    public function delete(Dictionnaires $dictionnaire){
        if ($dictionnaire instanceof Dictionnaires){
            $action = "Suppression du lexique";
            $content = "Lexique '{$dictionnaire->getLexique()}' supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($dictionnaire);
            $this->em->flush();
            return true;
        }
        return false;
    }
}