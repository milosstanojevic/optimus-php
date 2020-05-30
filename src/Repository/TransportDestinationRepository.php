<?php

namespace App\Repository;

use App\Entity\TransportDestination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransportDestination|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportDestination|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportDestination[]    findAll()
 * @method TransportDestination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportDestinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransportDestination::class);
    }

    // /**
    //  * @return TransportDestination[] Returns an array of TransportDestination objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransportDestination
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
