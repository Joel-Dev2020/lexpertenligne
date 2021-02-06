<?php

namespace App\Managers;

use App\Entity\User;
use App\Interfaces\LogsInterface;
use App\Repository\UserRepository;
use App\Services\PasswordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Security;

class UserManagers
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var PasswordService
     */
    protected $passwordService;
    /**
     * @var UserRepository
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
     * UserManagers constructor.
     * @param EntityManagerInterface $em
     * @param PasswordService $passwordService
     * @param UserRepository $repository
     * @param LogsInterface $logs
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $em,
        PasswordService $passwordService,
        UserRepository $repository,
        LogsInterface $logs,
        Security $security
    )
    {
        $this->em = $em;
        $this->passwordService = $passwordService;
        $this->repository = $repository;
        $this->logs = $logs;
        $this->security = $security;
    }


    /**
     * @param string $email
     * @return User|null
     */
    public function checkEmail(string $email){
        /**
         * @var $user User
         */
        $user = $this->repository->findOneBy(['email' => $email]);
        if ($user){
            return $user;
        }
        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function registerAccount(User $user){
        if ($user instanceof User){
            if ($this->checkEmail($user->getEmail())){
                throw new BadRequestException('Cette adresse email existe déjà');
            }
            $user->setUsername($user->getUsername());
            $password = $this->passwordService->encode($user, $user->getPassword());
            $user->setPassword($password);
            $user->setEnabled(true);
            $user->setEmail($user->getEmail());
            $this->em->persist($user);
            $this->em->flush();
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function activeAccount(User $user){
        if ($user instanceof User){
            $user->setEnabled(($user->getEnabled()) ? false:true);
            $this->em->persist($user);
            $this->em->flush();
            $action = "Activation/Désactivation de compte";
            $content = "Le compte de {$user->getUsername()} a été activé/désactivé par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function editAccount(User $user){
        if ($user instanceof User){
            $this->em->flush();
            $action = "Modification de compte";
            $content = "Le compte de {$user->getUsername()} a été modifié par " . $this->security->getUser()->getUsername();
            $this->logs->edit($action, $content,"orange", "edit");
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAccount(User $user){
        if ($user instanceof User){
            $action = "Suppression d'un compte utilisateur";
            $content = "Compte utilisateur '{$user->getUsername()}' supprimé par " . $this->security->getUser()->getUsername();
            $this->logs->delete($action, $content,"red", "trash");
            $this->em->remove($user);
            $this->em->flush();
            return true;
        }
        return false;
    }
}