<?php

namespace App\Repository;

use App\Entity\WarehouseArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WarehouseArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseArticle[]    findAll()
 * @method WarehouseArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseArticle::class);
    }

    // /**
    //  * @return WarehouseArticle[] Returns an array of WarehouseArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WarehouseArticle
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
