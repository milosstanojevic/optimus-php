<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $article_repository;

    public function __construct(ArticleRepository $article_repository)
    {
        $this->article_repository = $article_repository;
    }

    /**
     * @Route("/articles", name="get_all_articles", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $articles = $this->article_repository->findAll();
        $data = [];

        foreach ($articles as $article) {
            $data[] = $article->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/articles", name="add_article", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('name', $data)) {
            throw new UnprocessableEntityHttpException('Name required');
        }

        $article = $this->article_repository->saveArticle($data);

        return $this->json($article->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/articles/{id}", name="update_article", methods={"PUT"})
     * @param  int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $article = $this->article_repository->findOneBy(['id' => $id]);

        if (!$article) {
            throw new NotFoundHttpException('Article not found.');
        }

        $updated_article = $this->article_repository->updateArticle($article, json_decode($request->getContent(), true));

        return $this->json($updated_article->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/articles/{id}", name="get_one_article", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getArticle($id): JsonResponse
    {
        $warehouse = $this->article_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Article not found.');
        }

        return $this->json($warehouse->toArray(), Response::HTTP_OK);
    }
}
