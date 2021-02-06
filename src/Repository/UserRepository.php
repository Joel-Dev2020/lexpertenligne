<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param $role
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function userCount(string $role): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id) as count')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%' .$role. '%')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @param $role
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function userLockedCount($role): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id) as count')
            ->andWhere('u.enabled = 0')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%' .$role. '%')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @param $role
     * @return int|mixed|string
     */
    public function getClients($role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%' .$role. '%')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $role
     * @return int|mixed|string
     */
    public function getLastClients($role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%' .$role. '%')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function countOnlineUsers() {

        $date5mins = new \DateTime('5 minutes ago');

        $builder = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.lastActivity > :date5mins')
            ->setParameter('date5mins', $date5mins);

        return $builder->getQuery()->getSingleScalarResult();

    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
