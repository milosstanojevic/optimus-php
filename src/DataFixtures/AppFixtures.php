<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Regal;
use App\Entity\RegalPosition;
use App\Entity\Warehouse;
use App\Entity\WarehouseArticle;
use App\Repository\RegalPositionRepository;
use App\Repository\RegalRepository;
use App\Repository\WarehouseArticleRepository;
use App\Repository\WarehouseRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var RegalRepository
     */
    private $regalRepository;

    /**
     * @var RegalPositionRepository
     */
    private $regalPositionRepository;

    /**
     * @var WarehouseRepository
     */
    private $warehouseRepository;

    /**
     * @var WarehouseArticleRepository
     */
    private $warehouseArticleRepository;

    public function __construct(
        RegalRepository $regalRepository,
        RegalPositionRepository $regalPositionRepository,
        WarehouseRepository $warehouseRepository,
        WarehouseArticleRepository $warehouseArticleRepository
    )
    {
        $this->regalRepository = $regalRepository;
        $this->regalPositionRepository = $regalPositionRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->warehouseArticleRepository = $warehouseArticleRepository;
    }

    public function load(ObjectManager $manager)
    {
        ini_set('memory_limit', '512M');

        $warehouse_min = 1;
        $warehouse_max = 1;
        $regal_min = 1;
        $regal_max = rand(5, 35);
        $regal_position_min = 1;
        $regal_position_max = rand(7, 40);
        $article_min = 1;
        $article_max = 500;
        $warehouse_articles_min = 1;
        $warehouse_articles_max = rand(5, 80);

        for ($warehouseId = $warehouse_min; $warehouseId <= $warehouse_max; $warehouseId++) {
            $data = [
                'name' => "Warehouse $warehouseId",
                'description' => "Warehouse desc $warehouseId",
                'address' => "address $warehouseId",
            ];

            $this->warehouseRepository->saveWarehouse($data);
            for ($regalId = $regal_min; $regalId <= $regal_max; $regalId++) {
                $this->regalRepository->saveRegal([
                    'name' => "Regal $regalId",
                    'warehouse_id' => $warehouseId,
                ]);

                for ($i = $regal_position_min; $i <= $regal_position_max; $i++) {
                    $regal_position = new RegalPosition();
                    $regal_position->setName("Position $i");
                    $regal_position->setRegalId(rand($regal_min, $regal_max));
                    $this->regalPositionRepository->saveRegalPosition([
                        'name' => "Position $i",
                        'regal_id' => $regalId,
                    ]);
                }
            }
        }

        for ($i = $article_min; $i <= $article_max; $i++) {
            $d = new DateTime();
            $article = new Article();
            $article->setName("Article $i");
            $article->setDescription("Description $i");
            $article->setBarcode(rand(100000, 999999));
            $article->setUnit('Kg');
            $article->setCreatedAt($d->setTimestamp($d->getTimestamp() + $i));
            $article->setUpdatedAt($d->setTimestamp($d->getTimestamp() + $i));
            $manager->persist($article);
        }

        $warehouses = $this->warehouseRepository->findAll();

        foreach ($warehouses as $warehouse) {
            $warehouseId = $warehouse->getId();
            $regals = $this->regalRepository->findBy(['warehouse_id' => $warehouseId]);
            foreach ($regals as $regal) {
                $regalPositions = $this->regalPositionRepository->findBy(['regal_id' => $regal->getId()]);
                foreach ($regalPositions as $regalPosition) {
                    for ($i = $warehouse_articles_min; $i <= $warehouse_articles_max; $i++) {
                        $articleId = $i + $regalPosition->getId();
                        if ($article_min <= $articleId && $articleId <= $article_max) {
                            $warehouseArticle = new WarehouseArticle();
                            $warehouseArticle->setWarehouseId($warehouseId);
                            $warehouseArticle->setArticleId($articleId);
                            $warehouseArticle->setRegalId($regalPosition->getRegalId());
                            $warehouseArticle->setRegalPositionId($regalPosition->getId());
                            $warehouseArticle->setQuantity(rand(100, 5000));
                            $manager->persist($warehouseArticle);
                        }
                    }
                }
            }
        }

        $manager->flush();
    }
}
