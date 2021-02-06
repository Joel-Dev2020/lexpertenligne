<?php

namespace App\Repository\Formations;

use App\Entity\Formations\Mediasformations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mediasformations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mediasformations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mediasformations[]    findAll()
 * @method Mediasformations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediasformationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mediasformations::class);
    }

    // /**
    //  * @return Mediasformations[] Returns an array of Mediasformations objects
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
    public function findOneBySomeField($value): ?Mediasformations
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
