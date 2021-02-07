<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Article::class);
        $this->manager = $manager;
    }

    /**
     * @param array $data
     * @return Article
     */
    public function saveArticle(array $data)
    {
        $article = new Article();

        $article
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setBarcode($data['bar_code'])
            ->setUnit($data['unit']);

        $this->manager->persist($article);
        $this->manager->flush();

        return $article;
    }

    /**
     * @param Article $article
     * @param array   $data
     * @return Article
     */
    public function updateArticle(Article $article, array $data): Article
    {
        empty($data['name']) ? true : $article->setName($data['name']);
        empty($data['description']) ? true : $article->setDescription($data['description']);
        empty($data['bar_code']) ? true : $article->setBarcode($data['bar_code']);
        empty($data['unit']) ? true : $article->setUnit($data['unit']);

        $this->manager->persist($article);
        $this->manager->flush();

        return $article;
    }

    public function deleteArticle(Article $article):void
    {
        $this->manager->remove($article);
        $this->manager->flush();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
