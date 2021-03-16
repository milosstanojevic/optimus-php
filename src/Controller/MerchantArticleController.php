<?php

namespace App\Controller;

use App\Repository\MerchantArticleRepository;
use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
}
