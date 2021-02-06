<?php

namespace App\Repository\Articles;

use App\Entity\Articles\DTParties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DTParties|null find($id, $lockMode = null, $lockVersion = null)
 * @method DTParties|null findOneBy(array $criteria, array $orderBy = null)
 * @method DTParties[]    findAll()
 * @method DTParties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DTPartiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DTParties::class);
    }

    // /**
    //  * @return DTParties[] Returns an array of DTParties objects
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
    public function findOneBySomeField($value): ?DTParties
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
