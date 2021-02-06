<?php

namespace App\Repository\Blogs;

use App\Entity\Blogs\Mediasblogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mediasblogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mediasblogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mediasblogs[]    findAll()
 * @method Mediasblogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediasblogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mediasblogs::class);
    }

    // /**
    //  * @return Mediasblogs[] Returns an array of Mediasblogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mediasblogs
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
