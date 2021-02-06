<?php

namespace App\Repository\Articles;

use App\Entity\Articles\DTCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DTCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method DTCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method DTCategories[]    findAll()
 * @method DTCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DTCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DTCategories::class);
    }

    // /**
    //  * @return DTCategories[] Returns an array of DTCategories objects
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
    public function findOneBySomeField($value): ?DTCategories
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
