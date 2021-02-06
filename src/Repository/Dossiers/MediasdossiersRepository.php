<?php

namespace App\Repository\Dossiers;

use App\Entity\Dossiers\Mediasdossiers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mediasdossiers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mediasdossiers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mediasdossiers[]    findAll()
 * @method Mediasdossiers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediasdossiersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mediasdossiers::class);
    }

    // /**
    //  * @return Mediasdossiers[] Returns an array of Mediasdossiers objects
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
    public function findOneBySomeField($value): ?Mediasdossiers
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
