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
    public function saveArticle(array $data): Article
    {
        $article = new Article();

        $this->attributesSetter($article, $data);

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
        $this->attributesSetter($article, $data);

        $this->manager->persist($article);
        $this->manager->flush();

        return $article;
    }

    public function deleteArticle(Article $article): void
    {
        $this->manager->remove($article);
        $this->manager->flush();
    }

    /**
     * @param Article $article
     * @param array $attributes
     */
    private function attributesSetter(Article $article, array $attributes)
    {
        !array_key_exists('name', $attributes)
            ? true
            : $article->setName($attributes['name']);
        !array_key_exists('description', $attributes)
            ? true
            : $article->setDescription($attributes['description']);
        !array_key_exists('bar_code', $attributes)
            ? true
            : $article->setBarcode($attributes['bar_code']);
        !array_key_exists('unit', $attributes)
            ? $article->setUnit('Kg')
            : $article->setUnit($attributes['unit']);

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
