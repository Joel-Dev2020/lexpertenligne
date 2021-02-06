<?php

namespace App\Repository\Blogs;

use App\Entity\Blogs\Categoriesblogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categoriesblogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoriesblogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoriesblogs[]    findAll()
 * @method Categoriesblogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesblogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoriesblogs::class);
    }

    // /**
    //  * @return Categoriesblogs[] Returns an array of Categoriesblogs objects
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
    public function findOneBySomeField($value): ?Categoriesblogs
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
