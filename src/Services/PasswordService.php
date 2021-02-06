<?php


namespace App\Services;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * PasswordService constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function encode(object $entity, string $password): string {
        return $this->userPasswordEncoder->encodePassword($entity, $password);
    }

    /**
     * @param string $password
     * @return string
     */
    public function formatRequirement(string $password): string {
        return preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $password);
    }

    public function isValid(object $entity, string $password): bool {
        return $this->userPasswordEncoder->isPasswordValid($entity, $password);
    }
}