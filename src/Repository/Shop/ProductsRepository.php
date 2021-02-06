<?php

namespace App\Repository\Shop;

use App\Data\ProductsSearchFilterData;
use App\Data\SearchData;
use App\Entity\Shop\Categories;
use App\Entity\Shop\Metakeywords;
use App\Entity\Shop\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Products::class);
        $this->paginator = $paginator;
    }

    public function findProductsPromos()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.pricepromo != :product')
            ->setParameter('product', 0)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getRandom(Products $product, $limit)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id != :product')
            ->setParameter('product', $product)
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function latest(int $limit)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
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
            12
        );
    }

    /**
     * @param SearchData $search
     * @return QueryBuilder
     */
    private function getSearchQuery(SearchData $search): QueryBuilder{
        $query =  $this->createQueryBuilder('p')
            ->andWhere('p.online = 1')/*
            ->andWhere('p.publishedAt <= CURRENT_TIMESTAMP()')*/
        ;

        if (!empty($search->q)){
            $query = $query
                ->andWhere('MATCH_AGAINST(p.name, p.extrait, p.description) AGAINST(:q boolean)>0')
                ->setParameters([
                    'q' => "%{$search->q}%"
                ])
            ;
        }

        return $query;
    }

    /**
     * @param ProductsSearchFilterData $search
     * @return PaginationInterface
     */
    public function findProductsSearchFilter(ProductsSearchFilterData $search): PaginationInterface
    {
        $query = $this->getProductsSearchFilterQuery($search)->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            12
        );
    }

    /**
     * Réccupère le prix minimum et maximum correspondant à une recherche
     * @param ProductsSearchFilterData $search
     * @return integer[]
     */
    public function findMinMax(ProductsSearchFilterData $search): array
    {
        $result = $this->getProductsSearchFilterQuery($search, true)
            ->select('MIN(p.price) as min', 'MAX(p.price) as max')
            ->getQuery()
            ->getScalarResult()
        ;
        return [(int)$result[0]['min'], (int)$result[0]['max']];
    }

    /**
     * @param ProductsSearchFilterData $search
     * @param bool $ignorePrice
     * @return QueryBuilder
     */
    private function getProductsSearchFilterQuery(ProductsSearchFilterData $search, $ignorePrice = false): QueryBuilder{
        $query =  $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->leftJoin('p.categories', 'c')
        ;

        if (!empty($search->q)){
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%")
            ;
        }

        if (!empty($search->min) && $ignorePrice === false){
            $query = $query
                ->andWhere('p.price >= :min')
                ->setParameter('min', $search->min)
            ;
        }

        if (!empty($search->max) && $ignorePrice === false){
            $query = $query
                ->andWhere('p.price <= :max')
                ->setParameter('max', $search->max)
            ;
        }

        if (!empty($search->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories)
            ;
        }

        return$query;
    }

    /**
     * @param Categories $categorie
     * @param int $page
     * @return PaginationInterface
     */
    public function findCategories(Categories $categorie, int $page): PaginationInterface
    {
        $query = $this->getCategoriesQuery($categorie)->getQuery();
        return $this->paginator->paginate(
            $query,
            $page,
            21
        );
    }

    /**
     * @param Categories $categorie
     * @return QueryBuilder
     */
    private function getCategoriesQuery(Categories $categorie): QueryBuilder{
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.categories', 'c')
            ->andWhere('p.online = 1')
        ;
        if (!empty($categorie)){
            $query = $query
                ->andWhere('c.id = :categorie')
                ->setParameters(['categorie' => $categorie])
                ->orderBy('p.id', 'DESC')
            ;
        }
        return $query;
    }

    /**
     * @param Metakeywords $metakeyword
     * @param int $page
     * @return PaginationInterface
     */
    public function findTags(Metakeywords $metakeyword, int $page): PaginationInterface
    {
        $query = $this->getTagsQuery($metakeyword)->getQuery();
        return $this->paginator->paginate(
            $query,
            $page,
            21
        );
    }

    /**
     * @param Metakeywords $metakeyword
     * @return QueryBuilder
     */
    private function getTagsQuery(Metakeywords $metakeyword): QueryBuilder{
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.metakeywords', 'me')
            ->andWhere('p.online = 1')
        ;
        if (!empty($metakeyword)){
            $query = $query
                ->andWhere('me.id = :metakeywords')
                ->setParameters(['metakeywords' => $metakeyword])
                ->orderBy('p.id', 'DESC')
            ;
        }
        return $query;
    }

    /**
     * @param array $datas
     * @return int|mixed|string
     */
    public function getProducts(array $datas)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id IN (:datas)')
            ->setParameters(['datas' => $datas])
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $limit
     * @return int|mixed|string
     */
    public function features(int $limit)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.featured = :featured')
            ->setParameters(['featured' => 1])
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $limit
     * @return int|mixed|string
     */
    public function newProducts(int $limit)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.online = 1')
            ->andWhere('p.nouveau = :nouveau')
            ->setParameters(['nouveau' => 1])
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $limit
     * @param Products $products
     * @param Categories $categoriesproducts
     * @return int|mixed|string
     */
    public function relatedProducts(int $limit, Products $products, Categories $categoriesproducts)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'cat')
            ->join('p.categories', 'cat')
            ->andWhere('p.online = 1')
            ->andWhere('p.id <> :product')
            ->andWhere('p.categories = :categories')
            ->setParameters(['product' => $products, 'categories' => $categoriesproducts])
            ->orderBy('p.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Products $products
     * @return int|mixed|string
     */
    public function associiatedProducts(Products $products)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.online = 1')
            ->andWhere('p.association = :product')
            ->setParameters(['product' => $products])
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Metakeywords $metakeywords
     * @return int|mixed|string
     */
    public function findProductsByTags(Metakeywords $metakeywords)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.metakeywords', 'me')
            ->andWhere('p.online = 1')/*
            ->andWhere('p.publishedAt <= CURRENT_TIMESTAMP()')*/
            ->andWhere('me.id = :metakeywords')
            ->setParameters(['metakeywords' => $metakeywords])
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Categories $categories
     * @return int|mixed|string
     */
    public function findProductsByCategories(Categories $categories)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.categories', 'c')
            ->andWhere('p.online = 1')/*
            ->andWhere('p.publishedAt <= CURRENT_TIMESTAMP()')*/
            ->andWhere('c.id = :categorie')
            ->setParameters(['categorie' => $categories])
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findProductsByed()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.online = 1')
            ->andWhere('p.payementAt <= CURRENT_TIMESTAMP()')
            ->setMaxResults(5)
            ->orderBy('p.payementAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function countProduct()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as total')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }


    /**
     * @param int $qteSeuil
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findCountSeuilQteProducts(int $qteSeuil)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as total')
            ->where('p.quantity < :qteSeuil')
            ->setParameter('qteSeuil', $qteSeuil)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }


    /**
     * @param int $qteSeuil
     * @return int|mixed|string
     */
    public function findProductsSeuilQte(int $qteSeuil)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.quantity < :qteSeuil')
            ->setParameter('qteSeuil', $qteSeuil)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Products $product
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function prev(Products $product)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id < :product')
            ->setParameters(['product' => $product])
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }


    /**
     * @param Products $product
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function next(Products $product)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id > :product')
            ->setParameters(['product' => $product])
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
