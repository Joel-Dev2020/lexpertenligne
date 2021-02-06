<?php

namespace App\Repository\Articles;

use App\Entity\Articles\DTChapitres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DTChapitres|null find($id, $lockMode = null, $lockVersion = null)
 * @method DTChapitres|null findOneBy(array $criteria, array $orderBy = null)
 * @method DTChapitres[]    findAll()
 * @method DTChapitres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DTChapitresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DTChapitres::class);
    }

    // /**
    //  * @return DTChapitres[] Returns an array of DTChapitres objects
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
    public function findOneBySomeField($value): ?DTChapitres
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
