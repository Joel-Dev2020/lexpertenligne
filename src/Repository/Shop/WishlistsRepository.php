<?php

namespace App\Repository\Shop;

use App\Entity\Shop\Wishlists;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @method Wishlists|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wishlists|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wishlists[]    findAll()
 * @method Wishlists[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishlistsRepository extends ServiceEntityRepository
{
    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * WishlistsRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wishlists::class);
    }

    public function findWishlist(User $user)
    {
        return $this->createQueryBuilder('w')
            ->select('COUNT(w.id) as count')
            ->andWhere('w.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    // /**
    //  * @return Wishlists[] Returns an array of Wishlists objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wishlists
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
