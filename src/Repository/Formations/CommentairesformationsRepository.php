<?php

namespace App\Repository\Formations;

use App\Entity\Formations\Commentairesformations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commentairesformations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentairesformations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentairesformations[]    findAll()
 * @method Commentairesformations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesformationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentairesformations::class);
    }

    // /**
    //  * @return Commentairesformations[] Returns an array of Commentairesformations objects
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
    public function findOneBySomeField($value): ?Commentairesformations
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
