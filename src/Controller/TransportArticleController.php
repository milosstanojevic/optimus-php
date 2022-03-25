<?php

namespace App\Controller;

use App\Repository\RegalPositionRepository;
use App\Repository\WarehouseArticleRepository;
use App\Repository\RegalRepository;
use App\Repository\TransportArticleRepository;
use App\Repository\TransportOrderArticleRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TransportArticleController extends AbstractController
{
    /**
     * @var TransportArticleRepository
     */
    private $transport_article_repository;

    /**
     * @var TransportOrderArticleRepository
     */
    private $transport_order_article_repository;

    /**
     * @var WarehouseRepository
     */
    private $warehouse_repo;

    /**
     * @var RegalRepository
     */
    private $regal_repo;

    /**
     * @var RegalPositionRepository
     */
    private $regal_p_repo;

    /**
     * @var WarehouseArticleRepository
     */
    private $warehouse_article_repo;

    public function __construct(
        TransportArticleRepository $transport_article_repository,
        TransportOrderArticleRepository $transport_order_article_repository,
        WarehouseRepository $warehouse_repo,
        RegalRepository $regal_repo,
        RegalPositionRepository $regal_p_repo,
        WarehouseArticleRepository $warehouse_article_repo
    ) {
        $this->transport_article_repository = $transport_article_repository;
        $this->transport_order_article_repository = $transport_order_article_repository;
        $this->warehouse_repo = $warehouse_repo;
        $this->regal_repo = $regal_repo;
        $this->regal_p_repo = $regal_p_repo;
        $this->warehouse_article_repo = $warehouse_article_repo;
    }
    /**
     * @Route("/transport-order-articles/{id}/articles", name="get_transport_articles", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getTransportArticles(int $id): JsonResponse
    {
        $transport_order_article = $this->transport_order_article_repository->findOneBy(['id' => $id]);

        if (!$transport_order_article) {
            throw new NotFoundHttpException('Transport order article not found.');
        }

        $transport_articles = $this->transport_article_repository->findBy(['transport_order_article_id' => $id]);

        $articles = [];
        $warehouse_ids = [];
        $regal_ids = [];
        $regal_p_ids = [];
        $warehouses = [];
        $regals = [];
        $regal_positions  = [];

        foreach ($transport_articles as $transport_article) {
            $articles[] = $transport_article->toArray();

            $warehouse_id = $transport_article->getWarehouseId();
            $regal_id = $transport_article->getRegalId();
            $regal_p_id = $transport_article->getRegalPositionId();

            if (!in_array($warehouse_id, $warehouse_ids)) {
                $warehouse_ids[] = $warehouse_id;
            }
            if (!in_array($regal_id, $regal_ids)) {
                $regal_ids[] = $regal_id;
            }
            if (!in_array($regal_p_id, $regal_p_ids)) {
                $regal_p_ids[] = $regal_p_id;
            }
        }

        if (count($warehouse_ids)) {
            $warehouses = $this->warehouse_repo->findByIds($warehouse_ids);
        }

        if (count($regal_ids)) {
            $regals = $this->regal_repo->findByIds($regal_ids);
        }

        if (count($regal_p_ids)) {
            $regal_positions = $this->regal_p_repo->findByIds($regal_p_ids);
        }

        return $this->json([
            'transport_articles' => $articles,
            'warehouses' => $warehouses,
            'regals' => $regals,
            'regal_positions' => $regal_positions
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/transport-order-articles/{id}/articles", name="add_transport_article", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function saveTransportArticle(int $id, Request $request): JsonResponse
    {
        $transport_order_article = $this->transport_order_article_repository->findOneBy(['id' => $id]);

        if (!$transport_order_article) {
            throw new NotFoundHttpException('Transport order article not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data) || !array_key_exists('article_id', $data)) {
            throw new UnprocessableEntityHttpException('Article required');
        }

        if (!array_key_exists('quantity', $data) || $data['quantity'] === 0) {
            throw new UnprocessableEntityHttpException('Quantity required');
        }

        $article = $this->transport_article_repository->saveTransportArticle(
            array_merge($data, ['transport_order_article_id' => $id])
        );

        $this
            ->transport_order_article_repository
            ->addTransportOrderQuantity($transport_order_article, $data['quantity']);

        $warehouse_article = $this->warehouse_article_repo->findOneBy([
            'warehouse_id' => $article->getWarehouseId(),
            'article_id' => $article->getArticleId(),
            'regal_id' => $article->getRegalId(),
            'regal_position_id' => $article->getRegalPositionId()
        ]);

        if ($warehouse_article) {
            $qty = $warehouse_article->getQuantity();
            $warehouse_article->setQuantity($qty >= $data['quantity'] ? $qty - $data['quantity'] : $qty);
            $this->warehouse_article_repo->updateWarehouseArticle($warehouse_article);
        }

        return $this->json($article->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/transport-order-articles/{id}/articles/{article_id}", name="edit_transport_article", methods={"PUT"})
     * @param int     $id
     * @param int     $article_id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTransportArticle(int $id, int $article_id, Request $request): JsonResponse
    {
        $transport_order_article = $this->transport_order_article_repository->findOneBy(['id' => $id]);

        if (!$transport_order_article) {
            throw new NotFoundHttpException('Transport order article not found.');
        }

        $transport_article = $this->transport_article_repository->findOneBy(['id' => $article_id]);

        if (!$transport_article) {
            throw new NotFoundHttpException('Transport article not found.');
        }

        $data = json_decode($request->getContent(), true);

        $article = $this->transport_article_repository->updateTransportArticle($transport_article, $data);

        return $this->json($article->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/transport-order-articles/{id}/articles/{article_id}", name="delete_transport_article", methods={"DELETE"})
     * @param int $id
     * @param int $article_id
     * @return JsonResponse
     */
    public function deleteMerchantArticle(int $id, int $article_id): JsonResponse
    {
        $transport_order_article = $this->transport_order_article_repository->findOneBy(['id' => $id]);

        if (!$transport_order_article) {
            throw new NotFoundHttpException('Transport order article not found.');
        }

        $transport_article = $this->transport_article_repository->findOneBy(['id' => $article_id]);

        if (!$transport_article) {
            throw new NotFoundHttpException('Transport article not found.');
        }

        $this->transport_article_repository->remove($transport_article);
        $this
            ->transport_order_article_repository
            ->removeTransportOrderQuantity($transport_order_article, $transport_article->getQuantity());

        $warehouse_article = $this->warehouse_article_repo->findOneBy([
            'warehouse_id' => $transport_article->getWarehouseId(),
            'article_id' => $transport_article->getArticleId(),
            'regal_id' => $transport_article->getRegalId(),
            'regal_position_id' => $transport_article->getRegalPositionId()
        ]);

        if ($warehouse_article) {
            $transport_article_qty = $transport_article->getQuantity();
            $qty = $warehouse_article->getQuantity();
            $warehouse_article->setQuantity($qty ? $qty + $transport_article_qty : $transport_article_qty);
            $this->warehouse_article_repo->updateWarehouseArticle($warehouse_article);
        }

        return $this->json($transport_article->toArray(), Response::HTTP_NO_CONTENT);
    }
}
