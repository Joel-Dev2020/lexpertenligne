<?php

namespace App\Repository\Pages;

use App\Entity\Pages\Pages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pages[]    findAll()
 * @method Pages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pages::class);
    }

    public function findPagesByCat(int $categorie_id)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->leftJoin('p.categories', 'c')
            ->andWhere('p.online = 1')
            ->andWhere('c.id = :categorie')
            ->setParameter('categorie', $categorie_id)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int|null $categorie_id
     * @param int $limit
     * @param Pages $page
     * @return int|mixed|string
     */
    public function findRand(?int $categorie_id, int $limit, Pages $page)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->leftJoin('p.categories', 'c')
            ->andWhere('p.online = 1')
            ->andWhere('p.id != :page')
            ->setParameter(':page', $page);
            if ($categorie_id){
                $query = $query
                    ->andWhere('c.id = :categorie')
                    ->setParameter(':categorie', $categorie_id);
            }
        $query = $query
            ->setMaxResults($limit)
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult()
            ;
        return $query;
    }

    public function latest(int $limit)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->leftJoin('p.categories', 'c')
            ->andWhere('p.online = :online')
            ->setParameters(['online' => 1])
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Pages[] Returns an array of Pages objects
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
    public function findOneBySomeField($value): ?Pages
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
