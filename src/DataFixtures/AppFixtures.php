<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Regal;
use App\Entity\RegalPosition;
use App\Entity\Warehouse;
use App\Entity\WarehouseArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $warehouse_min = 1;
        $warehouse_max = 3;
        $regal_min = 1;
        $regal_max = 50;
        $regal_position_min = 1;
        $regal_position_max = 500;
        $article_min = 1;
        $article_max = 100;
        $warehouse_articles_min = 1;
        $warehouse_articles_max = 5000;

        for ($i = $warehouse_min; $i <= $warehouse_max; $i++) {
            $warehouse = new Warehouse();
            $warehouse->setName("Warehouse $i");
            $warehouse->setDescription("Warehouse desc $i");
            $warehouse->setAddress("address $i");
            $manager->persist($warehouse);
        }

        for ($i = $article_min; $i <= $article_max; $i++) {
            $article = new Article();
            $article->setName("Article $i");
            $article->setDescription("Description $i");
            $article->setBarcode(rand(100000, 999999));
            $article->setUnit('Kg');
            $manager->persist($article);
        }

        for ($i = $regal_min; $i <= $regal_max; $i++) {
            $warehouse_id = rand($warehouse_min, $warehouse_max);
            $regal = new Regal();
            $regal->setName("Regal $i");
            $regal->setWarehouseId($warehouse_id);
            $manager->persist($regal);
        }

        for ($i = $regal_position_min; $i <= $regal_position_max; $i++) {
            $regal_position = new RegalPosition();
            $regal_position->setName("Position $i");
            $regal_position->setRegalId(rand($regal_min, $regal_max));
            $manager->persist($regal_position);
        }

        for ($i = $warehouse_articles_min; $i <= $warehouse_articles_max; $i++) {
            $warehouseArticle = new WarehouseArticle();
            $warehouseArticle->setWarehouseId(rand($warehouse_min, $warehouse_max));
            $warehouseArticle->setArticleId(rand($article_min, $article_max));
            $warehouseArticle->setRegalId(rand($regal_min, $regal_max));
            $warehouseArticle->setRegalPositionId(rand($regal_position_min, $regal_position_max));
            $warehouseArticle->setQuantity(rand(100, 5000));
            $manager->persist($warehouseArticle);
        }

        $manager->flush();
    }
}
