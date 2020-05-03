<?php

namespace App\Repository;

use App\Entity\RegalPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegalPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegalPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegalPosition[]    findAll()
 * @method RegalPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegalPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegalPosition::class);
    }

    // /**
    //  * @return RegalPosition[] Returns an array of RegalPosition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RegalPosition
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
