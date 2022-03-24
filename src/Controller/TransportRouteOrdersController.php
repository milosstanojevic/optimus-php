<?php

namespace App\Controller;

use App\Repository\TransportOrderRepository;
use App\Repository\TransportRouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TransportRouteOrdersController extends AbstractController
{
    private $route_repository;

    private $transport_order_repo;

    public function __construct(TransportRouteRepository $route_repository, TransportOrderRepository $transport_order_repo)
    {
        $this->route_repository = $route_repository;
        $this->transport_order_repo = $transport_order_repo;
    }

    /**
     * @Route("/transport-route-orders/{id}", name="get_all_transport_route_orders", methods={"GET"})
     */
    public function getAllTransportOrders(int $id): JsonResponse
    {
        $route = $this->route_repository->findOneBy(['id' => $id]);

        if (!$route) {
            throw new NotFoundHttpException('Transport Route not found.');
        }

        $orders = $this->transport_order_repo->findBy(['transport_id' => $id]);
        $data = [];

        foreach ($orders as $order) {
            $data[] = $order->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }
}
