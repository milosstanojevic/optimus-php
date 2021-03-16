<?php

namespace App\Controller;

use App\Repository\TransportArticleRepository;
use App\Repository\TransportDestinationRepository;
use App\Repository\TransportRouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TransportArticleController extends AbstractController
{
    private $transport_destination_repository;
    private $transport_route_repository;
    private $transport_article_repository;

    public function __construct(
        TransportDestinationRepository $transport_destination_repository,
        TransportRouteRepository $transport_route_repository,
        TransportArticleRepository $transport_article_repository
    )
    {
        $this->transport_destination_repository = $transport_destination_repository;
        $this->transport_route_repository = $transport_route_repository;
        $this->transport_article_repository = $transport_article_repository;
    }

    /**
     * @Route("/transport-route/{id}/destination/{destination_id}/articles", name="get_all_transport_destination_articles", methods={"GET"})
     * @param int $id
     * @param int $destination_id
     * @return JsonResponse
     */
    public function getAll(int $id, int $destination_id)
    {
        $transport = $this->transport_route_repository->findOneBy(['id' => $id]);

        if (!$transport) {
            throw new NotFoundHttpException('Transport not found.');
        }

        $destination = $this->transport_destination_repository->findOneBy(['id' => $destination_id]);

        if (!$destination) {
            throw new NotFoundHttpException('Destination not found.');
        }

        $transport_articles = $this->transport_article_repository
            ->findArticlesByTransportAndWarehouseId($id, $destination_id);

        $data = [];

        foreach ($transport_articles as $transport_article) {
            $data[] = $transport_article->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/transport-route/{id}/destination/{destination_id}/articles", name="add_transport_destination_article", methods={"POST"})
     * @param int     $id
     * @param int     $destination_id
     * @param Request $request
     * @return JsonResponse
     */
    public function add(int $id, int $destination_id, Request $request)
    {
        $transport = $this->transport_route_repository->findOneBy(['id' => $id]);

        if (!$transport) {
            throw new NotFoundHttpException('Transport not found.');
        }

        $destination = $this->transport_destination_repository->findOneBy(['id' => $destination_id]);

        if (!$destination) {
            throw new NotFoundHttpException('Destination not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (
            empty($data)
            || !array_key_exists('article_id', $data)
        ) {
            throw new UnprocessableEntityHttpException('Create transport article failed - Validation error.');
        }

        $article = $this->transport_article_repository
            ->saveTransportArticle(
                array_merge($data, ['transport_id' => $id, 'warehouse_id' => $destination_id])
            );

        return $this->json($article->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/transport-route/{id}/destination/{destination_id}/article/{transport_article_id}", name="delete_transport_destination_article", methods={"DELETE"})
     * @param int $id
     * @param int $destination_id
     * @param int $transport_article_id
     * @return JsonResponse
     */
    public function delete(int $id, int $destination_id, int $transport_article_id) {
        $transport = $this->transport_route_repository->findOneBy(['id' => $id]);

        if (!$transport) {
            throw new NotFoundHttpException('Transport not found.');
        }

        $destination = $this->transport_destination_repository->findOneBy(['id' => $destination_id]);

        if (!$destination) {
            throw new NotFoundHttpException('Destination not found.');
        }

        $transport_article = $this->transport_article_repository->findOneBy(['id' => $transport_article_id]);

        if (!$transport_article) {
            throw new NotFoundHttpException('Transport Article not found.');
        }

        $this->transport_article_repository->deleteTransportArticle($transport_article);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/transport-route/{id}/destination/{destination_id}/article/{transport_article_id}", name="update_transport_destination_article", methods={"PUT"})
     * @param int     $id
     * @param int     $destination_id
     * @param int     $transport_article_id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, int $destination_id, int $transport_article_id, Request $request) {
        $transport = $this->transport_route_repository->findOneBy(['id' => $id]);

        if (!$transport) {
            throw new NotFoundHttpException('Transport not found.');
        }

        $destination = $this->transport_destination_repository->findOneBy(['id' => $destination_id]);

        if (!$destination) {
            throw new NotFoundHttpException('Destination not found.');
        }

        $transport_article = $this->transport_article_repository->findOneBy(['id' => $transport_article_id]);

        if (!$transport_article) {
            throw new NotFoundHttpException('Transport Article not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (
            empty($data)
            || !array_key_exists('article_id', $data)
        ) {
            throw new UnprocessableEntityHttpException('Create transport article failed - Validation error.');
        }

        $updated_transport_article = $this->transport_article_repository
            ->updateTransportArticle(
                $transport_article,
                array_merge($data, ['transport_id' => $id, 'warehouse_id' => $destination_id])
            );

        return $this->json($updated_transport_article->toArray(), Response::HTTP_OK);
    }
}
