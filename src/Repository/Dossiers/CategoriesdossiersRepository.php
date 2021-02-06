<?php

namespace App\Repository\Dossiers;

use App\Entity\Dossiers\Categoriesdossiers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categoriesdossiers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoriesdossiers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoriesdossiers[]    findAll()
 * @method Categoriesdossiers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesdossiersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoriesdossiers::class);
    }

    // /**
    //  * @return Categoriesdossiers[] Returns an array of Categoriesdossiers objects
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
    public function findOneBySomeField($value): ?CategoriesDossiers
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
