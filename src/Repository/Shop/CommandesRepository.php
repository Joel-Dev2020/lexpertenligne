<?php

namespace App\Repository\Shop;

use App\Entity\User;
use App\Entity\Shop\Commandes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commandes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commandes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commandes[]    findAll()
 * @method Commandes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commandes::class);
    }

    public function getCountCommande(int $idStatus)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total')
            ->andWhere('c.status = :status')
            ->setParameter('status', $idStatus)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getLastCommande()
    {
        return $this->createQueryBuilder('c')
            ->select('c.reference')
            ->orderBy('c.reference', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getNotifsCommandes()
    {
        return $this->createQueryBuilder('c')
            ->where('c.notification = 0')
            ->setMaxResults(5)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function countCommandes()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCommande()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as count')
            ->where('c.notification = 0')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findTotalCommande()
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.totalttc) as totalttc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function findTotalCommandeToday()
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.totalttc) as totalttc')
            ->andWhere('c.date = :today')
            ->setParameter('today', date('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function findTotalPrix(User $client)
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.totalht) as totalht')
            ->andWhere('c.user = :client')
            ->setParameter('client', $client->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    /**
     * @param string $datedebut
     * @param string $datefin
     * @return int|mixed|string|null
     */
    public function getCommandeBetweenDates(string $datedebut, string $datefin)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date <= :datefin')
            ->andWhere('c.date >= :datedebut')
            ->andWhere('c.valider = 1')
            ->setParameters(['datefin' => $datefin, 'datedebut' => $datedebut])
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Commandes[] Returns an array of Commandes objects
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
    public function findOneBySomeField($value): ?Commandes
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
