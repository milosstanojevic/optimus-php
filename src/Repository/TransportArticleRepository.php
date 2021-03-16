<?php

namespace App\Repository;

use App\Entity\TransportArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransportArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportArticle[]    findAll()
 * @method TransportArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportArticleRepository extends ServiceEntityRepository
{
    private $manager;
    private $warehouse_article_repository;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        WarehouseArticleRepository $warehouse_article_repository
    ) {
        parent::__construct($registry, TransportArticle::class);
        $this->manager = $manager;
        $this->warehouse_article_repository = $warehouse_article_repository;
    }

    /**
     * @param int $transport_id
     * @param int $warehouse_id
     * @return TransportArticle[] Returns an array of TransportArticle objects
     */
    public function findArticlesByTransportAndWarehouseId(int $transport_id, int $warehouse_id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.transport_id = :transport')
            ->andWhere('t.warehouse_id = :warehouse')
            ->setParameter('warehouse', $warehouse_id)
            ->setParameter('transport', $transport_id)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param array $data
     * @return TransportArticle
     */
    public function saveTransportArticle(array $data)
    {
        $transport_article = new TransportArticle();

        $this->dataSetter($transport_article, $data);

        $article_id = $transport_article->getArticleId();

        if ($article_id > 0) {
            $article_warehouse_ids = $this->warehouse_article_repository->getWarehouseIdsByArticleId($article_id);
            if (count($article_warehouse_ids) === 1) {
                $transport_article->setWarehouseId($article_warehouse_ids[0]);
            }
        }

        $this->manager->persist($transport_article);
        $this->manager->flush();

        return $transport_article;
    }

    /**
     * @param TransportArticle $transport_article
     */
    public function deleteTransportArticle(TransportArticle $transport_article)
    {
        $this->manager->remove($transport_article);
        $this->manager->flush();
    }

    /**
     * @param TransportArticle $transport_article
     * @param array            $data
     * @return TransportArticle
     */
    public function updateTransportArticle(TransportArticle $transport_article, array $data)
    {
        $this->dataSetter($transport_article, $data);
        $this->manager->persist($transport_article);
        $this->manager->flush();

        return $transport_article;
    }

    /**
     * @param TransportArticle $transport_article
     * @param array            $data
     */
    private function dataSetter(TransportArticle $transport_article, array $data)
    {
        !array_key_exists('transport_id', $data) ? true : $transport_article->setTransportId($data['transport_id']);
        !array_key_exists('article_id', $data) ? true : $transport_article->setArticleId($data['article_id']);
        !array_key_exists('warehouse_id', $data) ? true : $transport_article->setWarehouseId($data['warehouse_id']);
        !array_key_exists('regal_id', $data) ? true : $transport_article->setRegalId($data['regal_id']);
        !array_key_exists('regal_position_id', $data) ? true : $transport_article->setRegalPositionId($data['regal_position_id']);
        !array_key_exists('quantity', $data) ? true : $transport_article->setQuantity($data['quantity']);
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
