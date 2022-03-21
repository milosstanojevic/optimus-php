<?php

namespace App\Repository;

use App\Entity\TransportOrderArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransportOrderArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportOrderArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportOrderArticle[]    findAll()
 * @method TransportOrderArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportOrderArticleRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, TransportOrderArticle::class);
        $this->manager = $manager;
    }

    public function saveTransportOrderArticle(array $attributes): TransportOrderArticle
    {
        $transport_order_article = new TransportOrderArticle();

        $this->attributesSetter($transport_order_article, $attributes);

        $this->manager->persist($transport_order_article);
        $this->manager->flush();

        return $transport_order_article;
    }

    public function updateTransportOrderArticle(
        TransportOrderArticle $transport_order_article,
        array $attributes
    ): TransportOrderArticle {
        $this->attributesSetter($transport_order_article, $attributes);
        $this->manager->persist($transport_order_article);
        $this->manager->flush();

        return $transport_order_article;
    }

    public function deleteTransportOrderArticle(TransportOrderArticle $transport_order_article): void
    {
        $this->manager->remove($transport_order_article);
        $this->manager->flush();
    }

    /**
     * @param TransportOrderArticle $transport_order_article
     * @param array $attributes
     */
    private function attributesSetter(TransportOrderArticle $transport_order_article, array $attributes)
    {
        !array_key_exists('transport_order_id', $attributes)
            ? true
            : $transport_order_article->setTransportOrderId($attributes['transport_order_id']);

        !array_key_exists('article_id', $attributes)
            ? true
            : $transport_order_article->setArticleId($attributes['article_id']);

        !array_key_exists('requested_quantity', $attributes)
            ? true
            : $transport_order_article->setRequestedQuantity($attributes['requested_quantity']);

        !array_key_exists('transport_quantity', $attributes)
            ? true
            : $transport_order_article->setTransportQuantity($attributes['transport_quantity']);

        !array_key_exists('reason', $attributes)
            ? true
            : $transport_order_article->setReason($attributes['reason']);
    }
    // /**
    //  * @return TransportOrderArticle[] Returns an array of TransportOrderArticle objects
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
    public function findOneBySomeField($value): ?TransportOrderArticle
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
