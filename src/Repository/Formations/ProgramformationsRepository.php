<?php

namespace App\Repository\Formations;

use App\Entity\Formations\Formations;
use App\Entity\Formations\Programformations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Programformations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programformations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programformations[]    findAll()
 * @method Programformations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramformationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programformations::class);
    }

    public function findPrograms(Formations $formation)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'f')
            ->leftJoin('p.formations', 'f')
            ->andWhere('f.id = :formation')
            ->setParameter(':formation', $formation)
            ->orderBy('p.ordre', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Programformations[] Returns an array of Programformations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Programformations
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
