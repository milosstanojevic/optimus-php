<?php

namespace App\Controller;

use App\Repository\TransportOrderArticleRepository;
use App\Repository\TransportOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TransportOrderArticlesController extends AbstractController
{
    private $transport_order_repo;
    private $transport_order_articles_repo;

    public function __construct(
        TransportOrderRepository $transport_order_repo,
        TransportOrderArticleRepository $transport_order_articles_repo
    ) {
        $this->transport_order_repo = $transport_order_repo;
        $this->transport_order_articles_repo = $transport_order_articles_repo;
    }

    /**
     * @Route("/transport-orders/{id}/articles", name="transport_order_articles", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getTransportOrderArticles(int $id): JsonResponse
    {
        $transport_order = $this->transport_order_repo->findOneBy(['id' => $id]);

        if (!$transport_order) {
            throw new NotFoundHttpException('Transport order not found.');
        }

        $transport_order_articles = $this->transport_order_articles_repo->findBy(['transport_order_id' => $id]);

        $data = [];

        foreach ($transport_order_articles as $transport_order_article) {
            $data[] = $transport_order_article->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/transport-orders/{id}/articles", name="add_transport_order_article", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function saveTransportOrderArticle(int $id, Request $request): JsonResponse
    {
        $transport_order = $this->transport_order_repo->findOneBy(['id' => $id]);

        if (!$transport_order) {
            throw new NotFoundHttpException('Transport order not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data) || !array_key_exists('article_id', $data)) {
            throw new UnprocessableEntityHttpException('Article required');
        }

        $article = $this->transport_order_articles_repo->saveTransportOrderArticle(
            array_merge($data, ['transport_order_id' => $id])
        );

        return $this->json($article->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/transport-orders/{id}/articles/{article_id}", name="edit_transport_order_article", methods={"PUT"})
     * @param int     $id
     * @param int     $article_id
     * @param Request $request
     * @return JsonResponse
     */
    public function editTransportOrderArticle(int $id, int $article_id, Request $request): JsonResponse
    {
        $transport_order = $this->transport_order_repo->findOneBy(['id' => $id]);

        if (!$transport_order) {
            throw new NotFoundHttpException('Transport order not found.');
        }

        $transport_order_article = $this
            ->transport_order_articles_repo
            ->findOneBy(['id' => $article_id, 'transport_order_id' => $id]);

        if (!$transport_order_article) {
            throw new NotFoundHttpException('Transport order article not found.');
        }

        $attributes = json_decode($request->getContent(), true);

        $article = $this->transport_order_articles_repo->updateTransportOrderArticle(
            $transport_order_article,
            $attributes
        );

        return $this->json($article->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/transport-orders/{id}/articles/{article_id}", name="edit_transport_order_article", methods={"DELETE"})
     * @param int $id
     * @param int $article_id
     * @return JsonResponse
     */
    public function deleteTransportOrderArticle(int $id, int $article_id): JsonResponse
    {
        $transport_order = $this->transport_order_repo->findOneBy(['id' => $id]);

        if (!$transport_order) {
            throw new NotFoundHttpException('Transport order not found.');
        }

        $transport_order_article = $this
            ->transport_order_articles_repo
            ->findOneBy(['id' => $article_id, 'transport_order_id' => $id]);

        if (!$transport_order_article) {
            throw new NotFoundHttpException('Transport order article not found.');
        }

        $this->transport_order_articles_repo->deleteTransportOrderArticle($transport_order_article);

        return $this->json($transport_order_article->toArray(), Response::HTTP_OK);
    }
}
