<?php

namespace App\Repository\Articles;

use App\Entity\Articles\DTTitres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DTTitres|null find($id, $lockMode = null, $lockVersion = null)
 * @method DTTitres|null findOneBy(array $criteria, array $orderBy = null)
 * @method DTTitres[]    findAll()
 * @method DTTitres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DTTitresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DTTitres::class);
    }

    // /**
    //  * @return DTTitres[] Returns an array of DTTitres objects
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
    public function findOneBySomeField($value): ?DTTitres
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
