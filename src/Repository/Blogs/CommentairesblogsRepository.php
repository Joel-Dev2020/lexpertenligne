<?php

namespace App\Repository\Blogs;

use App\Entity\Blogs\Commentairesblogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commentairesblogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentairesblogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentairesblogs[]    findAll()
 * @method Commentairesblogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesblogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentairesblogs::class);
    }

    // /**
    //  * @return Commentairesblogs[] Returns an array of Commentairesblogs objects
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
    public function findOneBySomeField($value): ?Commentairesblogs
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
