<?php

namespace App\Repository\Blogs;

use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Blogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blogs[]    findAll()
 * @method Blogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogsRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Blogs::class);
        $this->paginator = $paginator;
    }

    public function findViews()
    {
        return $this->createQueryBuilder('b')
            ->select('SUM(b.view) as countview')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findPublishedToDay()
    {
        return $this->createQueryBuilder('b')
            ->select('SUM(b.id) as count')
            ->andWhere('b.createdAt = :today')
            ->setParameter(':today', new \DateTime('now'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findBlogsbyCat($categorie)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c')
            ->leftJoin('b.categories', 'c')
            ->andWhere('c.id = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getRandom(Blogs $blog, $limit)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c', 'm')
            ->leftJoin('b.categories', 'c')
            ->leftJoin('b.mediasblogs', 'm')
            ->andWhere('b.id != :blog')
            ->setParameters(['blog' => $blog])
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function latest(int $limit)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c', 'm')
            ->leftJoin('b.categories', 'c')
            ->leftJoin('b.mediasblogs', 'm')
            ->andWhere('b.online = :online')
            ->setParameters(['online' => 1])
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function features(int $limit)
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'c', 'm')
            ->leftJoin('b.categories', 'c')
            ->leftJoin('b.mediasblogs', 'm')
            ->andWhere('b.online = :online')
            ->andWhere('p.featured = :featured')
            ->setParameters(['online' => 1, 'featured' => 1])
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param SearchData $search
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            90
        );
    }

    /**
     * @param SearchData $search
     * @return QueryBuilder
     */
    private function getSearchQuery(SearchData $search): QueryBuilder{
        $query =  $this->createQueryBuilder('b')
            ->select('b', 'c', 'm')
            ->leftJoin('b.categories', 'c')
            ->leftJoin('b.mediasblogs', 'm')
            ->andWhere('b.online = 1')
            /*->andWhere('p.publishedAt <= CURRENT_TIMESTAMP()')*/
        ;

        if (!empty($search->q)){
            $query = $query
                ->andWhere('MATCH_AGAINST(b.name, b.extrait, b.content) AGAINST(:q boolean)>0')
                ->orWhere('c.name IN (:categories)')
                ->setParameters([
                    'q' => "%{$search->q}%",
                    'categories' => $search->q
                ])
            ;
        }

        return $query;
    }

    // /**
    //  * @return Blogs[] Returns an array of Blogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blogs
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
