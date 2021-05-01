<?php

namespace App\Controller;

use App\Repository\TransportRouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TransportController extends AbstractController
{
    private $route_repository;

    public function __construct(TransportRouteRepository $route_repository)
    {
        $this->route_repository = $route_repository;
    }

    /**
     * @Route("/transport-routes", name="get_all_transport_routes", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $transport_routes = $this->route_repository->findAll();
        $data = [];

        foreach ($transport_routes as $route) {
            $data[] = $route->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/transport-routes", name="add_transport_route", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('name', $data)) {
            throw new UnprocessableEntityHttpException('Name required');
        }

        $route = $this->route_repository->saveTransportRoute($data);

        return $this->json($route->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/transport-routes/{id}", name="update_transport_route", methods={"PUT"})
     * @param  int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $route = $this->route_repository->findOneBy(['id' => $id]);

        if (!$route) {
            throw new NotFoundHttpException('Transport Route not found.');
        }

        empty($data['name']) ? true : $route->setName($data['name']);
        empty($data['description']) ? true : $route->setDescription($data['description']);

        $updated_route = $this->route_repository->updateTransportRoute($route);

        return $this->json($updated_route->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/transport-routes/{id}", name="get_one_transport_route", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getRoute(int $id): JsonResponse
    {
        $route = $this->route_repository->findOneBy(['id' => $id]);

        if (!$route) {
            throw new NotFoundHttpException('Transport Route not found.');
        }

        return $this->json($route->toArray(), Response::HTTP_OK);
    }
}
