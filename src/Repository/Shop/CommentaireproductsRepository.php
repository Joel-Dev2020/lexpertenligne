<?php

namespace App\Repository\Shop;

use App\Entity\Shop\Commentaireproducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commentaireproducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaireproducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaireproducts[]    findAll()
 * @method Commentaireproducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireproductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaireproducts::class);
    }

    // /**
    //  * @return Commentaireproducts[] Returns an array of Commentaireproducts objects
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
    public function findOneBySomeField($value): ?Commentaireproducts
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
