<?php

namespace App\Repository\Formations;

use App\Entity\Formations\Categoriesformations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categoriesformations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoriesformations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoriesformations[]    findAll()
 * @method Categoriesformations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesformationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoriesformations::class);
    }

    // /**
    //  * @return Categoriesformations[] Returns an array of Categoriesformations objects
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
    public function findOneBySomeField($value): ?CategoriesFormations
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
