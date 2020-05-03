<?php


namespace App\Controller;

use App\Repository\WarehouseArticleRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WarehouseArticleController extends AbstractController
{
    /**
     * @var WarehouseArticleRepository
     */
    private $warehouseArticleRepository;

    /**
     * @var WarehouseRepository
     */
    private $warehouseRepository;

    public function __construct(
        WarehouseArticleRepository $warehouseArticleRepository,
        WarehouseRepository $warehouseRepository
    )
    {
        $this->warehouseArticleRepository = $warehouseArticleRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * @Route("/warehouses/{id}/articles", name="get_warehouse_articles", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getWarehouseArticles($id): JsonResponse
    {
        $warehouse = $this->warehouseRepository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $warehouseArticles = $this->warehouseArticleRepository->findBy(['warehouse_id' => $id]);

        $data = [];

        foreach ($warehouseArticles as $warehouseArticle) {
            $data[] = $warehouseArticle->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}/regals/{regalId}/articles", name="get_warehouse_regal_articles", methods={"GET"})
     * @param int $id
     * @param int $regalId
     * @return JsonResponse
     */
    public function getWarehouseRegalArticles($id, $regalId): JsonResponse
    {
        $warehouse = $this->warehouseRepository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $warehouseArticles = $this->warehouseArticleRepository->findBy(['warehouse_id' => $id, 'regal_id' => $regalId]);

        $data = [];

        foreach ($warehouseArticles as $warehouseArticle) {
            $data[] = $warehouseArticle->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
