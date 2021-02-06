<?php

namespace App\Repository\Articles;

use App\Entity\Articles\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends NestedTreeRepository
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(Categories::class));
    }

    public function getRandom($limit)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function latest(int $limit)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCategoriesRandom($limit)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCats(?Categories $category ,int $limit)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :category')
            ->setParameter(':category', $category)
            ->orderBy('c.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }


    /**
     * @param array $datas
     * @return int|mixed|string
     */
    public function getCategories(array $datas)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id IN (:datas)')
            ->setParameters(['datas' => $datas])
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Categories[] Returns an array of Categories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categoriesarticles
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
