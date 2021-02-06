<?php

namespace App\Repository\Formations;

use App\Entity\Formations\Formations;
use App\Entity\Formations\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Formations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formations[]    findAll()
 * @method Formations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationsRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Formations::class);
        $this->paginator = $paginator;
    }

    public function findViews()
    {
        return $this->createQueryBuilder('f')
            ->select('SUM(f.view) as countview')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findPublishedToDay()
    {
        return $this->createQueryBuilder('f')
            ->select('SUM(f.id) as count')
            ->andWhere('f.createdAt = :today')
            ->setParameter(':today', new \DateTime('now'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findFeaturedFormation()
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.featured = 1')
            ->andWhere('f.online = 1')
            ->orderBy('f.updatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findFormationsbyCat($categorie)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'c')
            ->leftJoin('f.categories', 'c')
            ->andWhere('c.id = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getRandom(Formations $formarion, $limit)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'c', 'm')
            ->leftJoin('f.categories', 'c')
            ->leftJoin('f.mediasformations', 'm')
            ->andWhere('f.id != :formation')
            ->setParameters([':formation' => $formarion])
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function latest(int $limit)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'c', 'm')
            ->leftJoin('f.categories', 'c')
            ->leftJoin('f.mediasformations', 'm')
            ->andWhere('f.online = :online')
            ->setParameters(['online' => 1])
            ->orderBy('f.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function features(int $limit)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'c', 'm')
            ->leftJoin('f.categories', 'c')
            ->leftJoin('f.mediasformations', 'm')
            ->andWhere('f.online = :online')
            ->andWhere('f.featured = :featured')
            ->setParameters(['online' => 1, 'featured' => 1])
            ->orderBy('f.id', 'DESC')
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
        $query =  $this->createQueryBuilder('f')
            ->select('f', 'c', 'm')
            ->leftJoin('f.categories', 'c')
            ->leftJoin('f.mediasformations', 'm')
            ->andWhere('f.online = 1')
            /*->andWhere('d.publishedAt <= CURRENT_TIMESTAMP()')*/
        ;

        if (!empty($search->q)){
            $query = $query
                ->andWhere('MATCH_AGAINST(f.name, m.extrait, f.content) AGAINST(:q boolean)>0')
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
    //  * @return Formations[] Returns an array of Formations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formations
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
