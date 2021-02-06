<?php

namespace App\Repository\Baremes;

use App\Entity\Baremes\Secteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Secteurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Secteurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Secteurs[]    findAll()
 * @method Secteurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecteursRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Secteurs::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Secteurs[] Returns an array of Secteurs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Secteurs
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
