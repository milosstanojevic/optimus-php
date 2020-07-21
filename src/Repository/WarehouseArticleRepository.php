<?php

namespace App\Repository;

use App\Entity\WarehouseArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WarehouseArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseArticle[]    findAll()
 * @method WarehouseArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseArticleRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, WarehouseArticle::class);
        $this->manager = $manager;
    }

    /**
     * @param int $article_id
     * @return array
     */
    public function getWarehouseIdsByArticleId(int $article_id)
    {
        $data = [];
        $query = $this
            ->createQueryBuilder('w')
            ->andWhere('w.article_id = :val')
            ->setParameter('val', $article_id)
            ->select('w.warehouse_id')
            ->distinct()
            ->getQuery()
            ->getResult();

        foreach ($query as $item) {
            $data[] = $item['warehouse_id'];
        }

        return $data;
    }

    /**
     * @param array $data
     * @return WarehouseArticle
     */
    public function saveWarehouseArticle(array $data)
    {
        $warehouse_article = new WarehouseArticle();

        $found = $this->findOneBy([
           'warehouse_id' => $data['warehouse_id'],
           'article_id' => $data['article_id'],
           'regal_id' => $data['regal_id'],
           'regal_position_id' => $data['regal_position_id'],
        ]);

        if ($found) {
            $found->setQuantity($found->getQuantity() + $data['quantity']);
            return $this->updateWarehouseArticle($found);
        }

        $warehouse_article
            ->setWarehouseId($data['warehouse_id'])
            ->setArticleId($data['article_id'])
            ->setRegalId($data['regal_id'])
            ->setRegalPositionId($data['regal_position_id'])
            ->setQuantity($data['quantity']);

        $this->manager->persist($warehouse_article);
        $this->manager->flush();

        return $warehouse_article;
    }

    /**
     * @param WarehouseArticle $warehouse_article
     * @return WarehouseArticle
     */
    public function updateWarehouseArticle(WarehouseArticle $warehouse_article): WarehouseArticle
    {
        $this->manager->persist($warehouse_article);
        $this->manager->flush();

        return $warehouse_article;
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
