<?php

namespace App\Repository\Shop;

use App\Entity\Shop\Mediasproducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mediasproducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mediasproducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mediasproducts[]    findAll()
 * @method Mediasproducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediasproductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mediasproducts::class);
    }

    // /**
    //  * @return Mediasproducts[] Returns an array of Mediasproducts objects
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
    public function findOneBySomeField($value): ?Mediasproducts
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
