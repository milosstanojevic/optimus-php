<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/articles", name="get_all_articles", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $articles = $this->articleRepository->findAll();
        $data = [];

        foreach ($articles as $article) {
            $data[] = $article->toArray();
        }

        return $this->json($data, Response::HTTP_OK,   ['content-type' => 'application/json']);
    }
}
