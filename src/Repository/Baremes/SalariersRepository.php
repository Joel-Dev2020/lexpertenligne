<?php

namespace App\Repository\Baremes;

use App\Entity\Baremes\Salariers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Salariers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salariers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salariers[]    findAll()
 * @method Salariers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalariersRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Salariers::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Salariers[] Returns an array of Salariers objects
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
    public function findOneBySomeField($value): ?Salariers
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
