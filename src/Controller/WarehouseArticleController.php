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
    )
    {
        $this->warehouse_article_repository = $warehouse_article_repository;
        $this->warehouse_repository = $warehouse_repository;
    }

    /**
     * @Route("/warehouses/{id}/articles", name="get_warehouse_articles", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getWarehouseArticles($id): JsonResponse
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
    public function getWarehouseRegalArticles($id, $regalId): JsonResponse
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
     * @Route("/warehouses/{id}/article", name="add_warehouse_article", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function saveWarehouseArticle($id, Request $request): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('article_id', $data)) {
            throw new UnprocessableEntityHttpException('Article required');
        }

        $article = $this->warehouse_article_repository->saveWarehouseArticle($data);

        return $this->json($article->toArray(), Response::HTTP_CREATED);
    }
}
