<?php

namespace App\Repository\Dossiers;

use App\Entity\Dossiers\Dossiers;
use App\Entity\Dossiers\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Dossiers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossiers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossiers[]    findAll()
 * @method Dossiers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossiersRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Dossiers::class);
        $this->paginator = $paginator;
    }

    public function findViews()
    {
        return $this->createQueryBuilder('d')
            ->select('SUM(d.view) as countview')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findPublishedToDay()
    {
        return $this->createQueryBuilder('d')
            ->select('SUM(d.id) as count')
            ->andWhere('d.createdAt = :today')
            ->setParameter(':today', new \DateTime('now'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findFeaturedDossier()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.featured = 1')
            ->andWhere('d.online = 1')
            ->orderBy('d.updatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findDossiersbyCat($categorie)
    {
        return $this->createQueryBuilder('d')
            ->select('d', 'c')
            ->leftJoin('d.categories', 'c')
            ->andWhere('c.id = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getRandom(Dossiers $dossier, $limit)
    {
        return $this->createQueryBuilder('d')
            ->select('d', 'c', 'm')
            ->leftJoin('d.categories', 'c')
            ->leftJoin('d.mediasdossiers', 'm')
            ->andWhere('d.id != :dossier')
            ->setParameters([':dossier' => $dossier])
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function latest(int $limit)
    {
        return $this->createQueryBuilder('d')
            ->select('d', 'c', 'm')
            ->leftJoin('d.categories', 'c')
            ->leftJoin('d.mediasdossiers', 'm')
            ->andWhere('d.online = :online')
            ->setParameters(['online' => 1])
            ->orderBy('d.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function features(int $limit)
    {
        return $this->createQueryBuilder('d')
            ->select('d', 'c', 'm')
            ->leftJoin('d.categories', 'c')
            ->leftJoin('d.mediasdossiers', 'm')
            ->andWhere('d.online = :online')
            ->andWhere('d.featured = :featured')
            ->setParameters(['online' => 1, 'featured' => 1])
            ->orderBy('d.id', 'DESC')
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
        $query =  $this->createQueryBuilder('d')
            ->select('d', 'c', 'm')
            ->leftJoin('d.categories', 'c')
            ->leftJoin('d.mediasdossiers', 'm')
            ->andWhere('d.online = 1')
            /*->andWhere('d.publishedAt <= CURRENT_TIMESTAMP()')*/
        ;

        if (!empty($search->q)){
            $query = $query
                ->andWhere('MATCH_AGAINST(d.name, d.extrait, d.content) AGAINST(:q boolean)>0')
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
    //  * @return Dossiers[] Returns an array of Dossiers objects
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
    public function findOneBySomeField($value): ?Dossiers
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
