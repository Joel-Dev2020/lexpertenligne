<?php

namespace App\Repository\Blogs;

use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\Votesblogs;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Votesblogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Votesblogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Votesblogs[]    findAll()
 * @method Votesblogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VotesblogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Votesblogs::class);
    }
    public function findUserDisliked(?User $user, Blogs $blog)
    {
        return $this->createQueryBuilder('v')
            ->select('v', 'u', 'b')
            ->leftJoin('v.user', 'u')
            ->leftJoin('v.blogs', 'b')
            ->andWhere('b.id = :blog')
            ->setParameter('blog', $blog)
            ->andWhere('u.id = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findDisliked(Blogs $blog)
    {
        return $this->createQueryBuilder('v')
            ->select('v', 'u', 'b')
            ->leftJoin('v.user', 'u')
            ->leftJoin('v.blogs', 'b')
            ->andWhere('b.id = :blog')
            ->setParameter('blog', $blog)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Votesblogs[] Returns an array of Votesblogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Votesblogs
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
