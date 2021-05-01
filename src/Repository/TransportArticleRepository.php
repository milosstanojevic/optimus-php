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
     * @param array $data
     * @return TransportArticle
     */
    public function saveTransportArticle(array $data): TransportArticle
    {
        $transport_article = new TransportArticle();

        $this->dataSetter($transport_article, $data);

        $this->manager->persist($transport_article);
        $this->manager->flush();

        return $transport_article;
    }

    /**
     * @param TransportArticle $transport_article
     */
    public function deleteTransportArticle(TransportArticle $transport_article): void
    {
        $this->manager->remove($transport_article);
        $this->manager->flush();
    }

    /**
     * @param TransportArticle $transport_article
     * @param array            $data
     * @return TransportArticle
     */
    public function updateTransportArticle(TransportArticle $transport_article, array $data): TransportArticle
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
        !array_key_exists('transport_destination_id', $data) ? true : $transport_article->setTransportDestinationId($data['transport_destination_id']);
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
