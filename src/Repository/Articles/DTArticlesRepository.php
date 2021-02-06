<?php

namespace App\Repository\Articles;

use App\Entity\Articles\DTArticles;
use App\Entity\Articles\DTSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DTArticles|null find($id, $lockMode = null, $lockVersion = null)
 * @method DTArticles|null findOneBy(array $criteria, array $orderBy = null)
 * @method DTArticles[]    findAll()
 * @method DTArticles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DTArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DTArticles::class);
    }

    /**
     * @param DTSearch $search
     * @return Query
     */
    public function findAllVisibleQuery(DTSearch $search): Query {
        $query = $this->findVisibleQuery();
        if ($search->getSearch()){
            $query = $query
                ->andwhere('d.contenu_article LIKE  :search')
                ->setParameter('search', '%'.$search->getSearch().'%')
                ->orderBy('d.id', 'DESC');
        }
        if ($search->getDtcategories()){
            $query = $query
                ->andWhere("d.dtcategories = :dtcategories")
                ->setParameter("dtcategories", $search->getDtcategories());
        }
        return $query->getQuery();
    }

    private function findVisibleQuery(): QueryBuilder{
        return $this->createQueryBuilder('d')
            ->andWhere('d.online = 1');
    }

    // /**
    //  * @return DTArticles[] Returns an array of DTArticles objects
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
    public function findOneBySomeField($value): ?DTArticles
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
