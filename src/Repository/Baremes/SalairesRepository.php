<?php

namespace App\Repository\Baremes;

use App\Entity\Baremes\Salaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Salaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salaires[]    findAll()
 * @method Salaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalairesRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Salaires::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Salaires[] Returns an array of Salaires objects
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
    public function findOneBySomeField($value): ?Salaires
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
