<?php

namespace App\Repository;

use App\Entity\TransportArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    /**
     * @param TransportArticle $transport_article
     * @param array $attributes
     */
    private function attributesSetter(TransportArticle $transport_order_article, array $attributes)
    {
        !array_key_exists('warehouse_id', $attributes)
            ? true
            : $transport_order_article->setWarehouseId($attributes['warehouse_id']);

        !array_key_exists('article_id', $attributes)
            ? true
            : $transport_order_article->setArticleId($attributes['article_id']);

        !array_key_exists('transport_order_article_id', $attributes)
            ? true
            : $transport_order_article->setTransportOrderArticleId($attributes['transport_order_article_id']);

        !array_key_exists('warehouse_id', $attributes)
            ? true
            : $transport_order_article->setWarehouseId($attributes['warehouse_id']);

        !array_key_exists('regal_id', $attributes)
            ? true
            : $transport_order_article->setRegalId($attributes['regal_id']);

        !array_key_exists('regal_position_id', $attributes)
            ? true
            : $transport_order_article->setRegalPositionId($attributes['regal_position_id']);

        !array_key_exists('quantity', $attributes)
            ? true
            : $transport_order_article->setQuantity($attributes['quantity']);
    }

    public function saveTransportArticle(array $attributes): TransportArticle
    {
        $transport_article = new TransportArticle();

        $this->attributesSetter($transport_article, $attributes);

        $this->_em->persist($transport_article);
        $this->_em->flush();

        return $transport_article;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TransportArticle $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function updateTransportArticle(
        TransportArticle $transport_article,
        array $attributes
    ): TransportArticle {
        $this->attributesSetter($transport_article, $attributes);
        $this->_em->persist($transport_article);
        $this->_em->flush();

        return $transport_article;
    }

    public function remove(TransportArticle $entity, bool $flush = true): TransportArticle
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
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
