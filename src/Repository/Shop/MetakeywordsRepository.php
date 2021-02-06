<?php

namespace App\Repository\Shop;

use App\Entity\Shop\Metakeywords;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Metakeywords|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metakeywords|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metakeywords[]    findAll()
 * @method Metakeywords[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetakeywordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metakeywords::class);
    }

    // /**
    //  * @return Metakeywords[] Returns an array of Metakeywords objects
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
    public function findOneBySomeField($value): ?Metakeywords
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
