<?php

namespace App\Repository;

use App\Entity\MerchantArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MerchantArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method MerchantArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method MerchantArticle[]    findAll()
 * @method MerchantArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MerchantArticle::class);
    }

    // /**
    //  * @return MerchantArticle[] Returns an array of MerchantArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MerchantArticle
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
