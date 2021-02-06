<?php

namespace App\Repository;

use App\Entity\Publicites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Publicites|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publicites|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publicites[]    findAll()
 * @method Publicites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicitesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publicites::class);
    }

    // /**
    //  * @return Publicites[] Returns an array of Publicites objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Publicites
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
