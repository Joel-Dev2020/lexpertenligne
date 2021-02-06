<?php

namespace App\Repository\Documents;

use App\Entity\Documents\Documents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Documents|null find($id, $lockMode = null, $lockVersion = null)
 * @method Documents|null findOneBy(array $criteria, array $orderBy = null)
 * @method Documents[]    findAll()
 * @method Documents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Documents::class);
    }

    public function findDocsbyCat($categorie)
    {
        return $this->createQueryBuilder('d')
            ->select('d', 'c')
            ->leftJoin('d.categories', 'c')
            ->andWhere('d.online = 1')
            ->andWhere('c.id = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Documents[] Returns an array of Documents objects
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
    public function findOneBySomeField($value): ?Documents
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
