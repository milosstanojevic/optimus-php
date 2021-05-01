<?php

namespace App\Controller;

use App\Repository\MerchantArticleRepository;
use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MerchantArticleController extends AbstractController
{
    /**
     * @var MerchantArticleRepository
     */
    private $merchant_article_repository;

    /**
     * @var MerchantRepository
     */
    private $merchant_repository;

    public function __construct(
        MerchantArticleRepository $merchant_article_repository,
        MerchantRepository $merchant_repository
    )
    {
        $this->merchant_article_repository = $merchant_article_repository;
        $this->merchant_repository = $merchant_repository;
    }
    /**
     * @Route("/merchants/{id}/articles", name="get_merchant_articles", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getMerchantArticles(int $id): JsonResponse
    {
        $merchant = $this->merchant_repository->findOneBy(['id' => $id]);

        if (!$merchant) {
            throw new NotFoundHttpException('Merchant not found.');
        }

        $merchant_articles = $this->merchant_article_repository->findBy(['merchant_id' => $id]);

        $data = [];

        foreach ($merchant_articles as $merchant_article) {
            $data[] = $merchant_article->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/merchants/{id}/articles", name="add_merchant_article", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function saveMerchantArticles(int $id, Request $request): JsonResponse
    {
        $merchant = $this->merchant_repository->findOneBy(['id' => $id]);

        if (!$merchant) {
            throw new NotFoundHttpException('Merchant not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('article_id', $data)) {
            throw new UnprocessableEntityHttpException('Article required');
        }

        $article = $this->merchant_article_repository->saveMerchantArticle(
            array_merge($data, ['merchant_id' => $id])
        );

        return $this->json($article->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/merchants/{id}/articles/{article_id}", name="edit_merchant_article", methods={"PUT"})
     * @param int     $id
     * @param int     $article_id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateMerchantArticles(int $id, int $article_id, Request $request): JsonResponse
    {
        $merchant = $this->merchant_repository->findOneBy(['id' => $id]);

        if (!$merchant) {
            throw new NotFoundHttpException('Merchant not found.');
        }

        $merchant_article = $this->merchant_article_repository->findOneBy(['id' => $article_id]);

        if (!$merchant_article) {
            throw new NotFoundHttpException('Merchant article not found.');
        }

        $data = json_decode($request->getContent(), true);

        $article = $this->merchant_article_repository->updateMerchantArticle($merchant_article, $data);

        return $this->json($article->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/merchants/{id}/articles/{article_id}", name="delete_merchant_article", methods={"DELETE"})
     * @param int $id
     * @param int $article_id
     * @return JsonResponse
     */
    public function deleteMerchantArticles(int $id, int $article_id): JsonResponse
    {
        $merchant = $this->merchant_repository->findOneBy(['id' => $id]);

        if (!$merchant) {
            throw new NotFoundHttpException('Merchant not found.');
        }

        $merchant_article = $this->merchant_article_repository->findOneBy(['id' => $article_id]);

        if (!$merchant_article) {
            throw new NotFoundHttpException('Merchant article not found.');
        }

        $this->merchant_article_repository->deleteMerchantArticle($merchant_article);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
