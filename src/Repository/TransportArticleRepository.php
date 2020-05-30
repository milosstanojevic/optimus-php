<?php

namespace App\Repository;

use App\Entity\TransportArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransportArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportArticle[]    findAll()
 * @method TransportArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransportArticle::class);
    }

    // /**
    //  * @return TransportArticle[] Returns an array of TransportArticle objects
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
    public function findOneBySomeField($value): ?TransportArticle
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
