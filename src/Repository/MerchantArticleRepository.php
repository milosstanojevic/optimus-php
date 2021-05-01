<?php

namespace App\Repository;

use App\Entity\MerchantArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MerchantArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method MerchantArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method MerchantArticle[]    findAll()
 * @method MerchantArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantArticleRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, MerchantArticle::class);
        $this->manager = $manager;
    }

    public function saveMerchantArticle(array $attributes): MerchantArticle
    {
        $merchant_article = new MerchantArticle();

        $this->attributesSetter($merchant_article, $attributes);

        $this->manager->persist($merchant_article);
        $this->manager->flush();

        return $merchant_article;
    }

    public function updateMerchantArticle(
        MerchantArticle $merchant_article,
        array $attributes
    ): MerchantArticle
    {
        $this->attributesSetter($merchant_article, $attributes);
        $this->manager->persist($merchant_article);
        $this->manager->flush();

        return $merchant_article;
    }

    public function deleteMerchantArticle(MerchantArticle $merchant_article): void
    {
        $this->manager->remove($merchant_article);
        $this->manager->flush();
    }

    /**
     * @param MerchantArticle $merchant_article
     * @param array $attributes
     */
    private function attributesSetter(MerchantArticle $merchant_article, array $attributes)
    {
        !array_key_exists('merchant_id', $attributes)
            ? true
            : $merchant_article->setMerchantId($attributes['merchant_id']);
        !array_key_exists('article_id', $attributes)
            ? true
            : $merchant_article->setArticleId($attributes['article_id']);
        !array_key_exists('quantity', $attributes)
            ? true
            : $merchant_article->setQuantity($attributes['quantity']);
        !array_key_exists('warehouse_id', $attributes)
            ? true
            : $merchant_article->setWarehouseId($attributes['warehouse_id']);

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
