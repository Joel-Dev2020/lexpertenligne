<?php

namespace App\Repository\Documents;

use App\Entity\Documents\Categoriesdocuments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categoriesdocuments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoriesdocuments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoriesdocuments[]    findAll()
 * @method Categoriesdocuments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesdocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriesDocuments::class);
    }

    // /**
    //  * @return Categoriesdocuments[] Returns an array of Categoriesdocuments objects
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
    public function findOneBySomeField($value): ?Categoriesdocuments
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
