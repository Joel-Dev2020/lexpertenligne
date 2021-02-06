<?php

namespace App\Managers\Documents;

use App\Entity\Documents\Documents;
use App\Interfaces\LogsInterface;
use App\Repository\Documents\DocumentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class DocumentsManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var DocumentsRepository
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
     * DocumentsManagers constructor.
     * @param EntityManagerInterface $em
     * @param DocumentsRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        DocumentsRepository $repository,
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
     * @param Documents $document
     * @return bool
     */
    public function new(Documents $document){
        if ($document instanceof Documents){
            $this->em->persist($document);
            $this->em->flush();
            $action = "Ajout d'un nouveau document";
            $content = "Nouveau document '{$document->getName()}' ajouté par " . $this->security->getUser()->getUsername();
            $this->logs->add($action, $content,"green", "check");
            return true;
        }
        return false;
    }

    /**
     * @param Documents $document
     * @return bool
     */
    public function active(Documents $document){
        if ($document instanceof Documents){
            $document->setOnline(($document->getOnline()) ? false:true);
            $this->em->persist($document);
            $this->em->flush();
            $action = "Activation/Désactivation du document";
            $content = "Document {$document->getName()} a été activé/désactivé par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Documents $document
     * @return bool
     */
    public function edit(Documents $document){
        if ($document instanceof Documents){
            $this->em->flush();
            $action = "Modification d'un document";
            $content = "Document '{$document->getName()}' modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param Documents $document
     * @return bool
     */
    public function delete(Documents $document){
        if ($document instanceof Documents){
            $action = "Suppression d'un document";
            $content = "Document '{$document->getName()}' supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($document);
            $this->em->flush();
            return true;
        }
        return false;
    }
}