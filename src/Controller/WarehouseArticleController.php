<?php

namespace App\Controller;

use App\Repository\WarehouseArticleRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WarehouseArticleController extends AbstractController
{
    /**
     * @var WarehouseArticleRepository
     */
    private $warehouse_article_repository;

    /**
     * @var WarehouseRepository
     */
    private $warehouse_repository;

    public function __construct(
        WarehouseArticleRepository $warehouse_article_repository,
        WarehouseRepository $warehouse_repository
    ) {
        $this->warehouse_article_repository = $warehouse_article_repository;
        $this->warehouse_repository = $warehouse_repository;
    }

    /**
     * @Route("/warehouse-articles", name="get_all_warehouse_articles", methods={"GET"})
     * @return JsonResponse
     */
    public function getAllWarehouseArticles(): JsonResponse
    {

        $warehouseArticles = $this->warehouse_article_repository->findAll();

        $data = [];

        foreach ($warehouseArticles as $warehouseArticle) {
            $data[] = $warehouseArticle->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}/articles", name="get_warehouse_articles", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getWarehouseArticles(int $id): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $warehouseArticles = $this->warehouse_article_repository->findBy(['warehouse_id' => $id]);

        $data = [];

        foreach ($warehouseArticles as $warehouseArticle) {
            $data[] = $warehouseArticle->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}/regals/{regalId}/articles", name="get_warehouse_regal_articles", methods={"GET"})
     * @param int $id
     * @param int $regalId
     * @return JsonResponse
     */
    public function getWarehouseRegalArticles(int $id, int $regalId): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $warehouseArticles = $this->warehouse_article_repository->findBy(['warehouse_id' => $id, 'regal_id' => $regalId]);

        $data = [];

        foreach ($warehouseArticles as $warehouseArticle) {
            $data[] = $warehouseArticle->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}/articles", name="add_warehouse_article", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function saveWarehouseArticle(int $id, Request $request): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            throw new UnprocessableEntityHttpException('Not allowed. Empty attributes.');
        }

        if (!array_key_exists('article_id', $data)) {
            throw new UnprocessableEntityHttpException('Article required');
        }

        if (!array_key_exists('regal_id', $data)) {
            throw new UnprocessableEntityHttpException('Regal required');
        }

        if (!array_key_exists('regal_position_id', $data)) {
            throw new UnprocessableEntityHttpException('Regal Position required');
        }

        $article = $this->warehouse_article_repository->saveWarehouseArticle([
            'warehouse_id' => $id,
            'article_id' => $data['article_id'],
            'regal_id' => $data['regal_id'],
            'regal_position_id' => $data['regal_position_id'],
            'quantity' => $data['quantity'],
        ]);

        return $this->json($article->toArray(), Response::HTTP_CREATED);
    }
}
