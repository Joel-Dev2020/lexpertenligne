<?php

namespace App\Repository\Articles;

use App\Entity\Articles\DTSections;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DTSections|null find($id, $lockMode = null, $lockVersion = null)
 * @method DTSections|null findOneBy(array $criteria, array $orderBy = null)
 * @method DTSections[]    findAll()
 * @method DTSections[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DTSectionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DTSections::class);
    }

    // /**
    //  * @return DTSections[] Returns an array of DTSections objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DTSections
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
