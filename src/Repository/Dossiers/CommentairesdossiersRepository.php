<?php

namespace App\Repository\Dossiers;

use App\Entity\Dossiers\Commentairesdossiers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commentairesdossiers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentairesdossiers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentairesdossiers[]    findAll()
 * @method Commentairesdossiers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesdossiersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentairesdossiers::class);
    }

    // /**
    //  * @return Commentairesdossiers[] Returns an array of Commentairesdossiers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commentairesdossiers
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
