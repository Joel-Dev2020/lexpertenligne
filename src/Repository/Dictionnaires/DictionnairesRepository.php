<?php

namespace App\Repository\Dictionnaires;

use App\Entity\Dictionnaires\Dictionnaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dictionnaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dictionnaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dictionnaires[]    findAll()
 * @method Dictionnaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DictionnairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dictionnaires::class);
    }

    public function findLexiquesSearchs(string $search)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.lexique LIKE :search')
            ->setParameter('search', "{$search}%")
            ->orderBy('d.lexique', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Dictionnaires[] Returns an array of Dictionnaires objects
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
    public function findOneBySomeField($value): ?Dictionnaires
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
