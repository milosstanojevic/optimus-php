<?php

namespace App\Controller;

use App\Repository\TransportOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransportOrderController extends AbstractController
{
    private $transport_order_repo;

    public function __construct(TransportOrderRepository $transport_order_repo)
    {
        $this->transport_order_repo = $transport_order_repo;
    }

    /**
     * @Route("/transport-orders", name="get_all_transport_orders", methods={"GET"})
     */
    public function index(): Response
    {
        $orders = $this->transport_order_repo->findAll();
        $data = [];

        foreach ($orders as $order) {
            $data[] = $order->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/transport-orders/{parent}/{id}", name="get_parent_transport_orders", methods={"GET"})
     * @param string $parent
     * @param int $id
     * @return JsonResponse
     */
    public function getParentTransportOrders(string $parent, int $id): JsonResponse
    {
        $transport_orders = $this->transport_order_repo->findBy(['parent_id' => $id, 'parent' => $parent]);

        $data = [];

        foreach ($transport_orders as $transport_order) {
            $data[] = $transport_order->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/transport-orders", name="add_transport_order", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!empty($data) && (!array_key_exists('parent', $data) || !array_key_exists('parent_id', $data))) {
            throw new UnprocessableEntityHttpException('Destination not set');
        }

        $transport_order = $this->transport_order_repo->saveTransportOrder($data);

        return $this->json($transport_order->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/transport-orders/{id}", name="update_transport_order", methods={"PUT"})
     * @param  int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $transport_order = $this->transport_order_repo->findOneBy(['id' => $id]);

        if (!$transport_order) {
            throw new NotFoundHttpException('Transport order not found.');
        }

        $updated_transport_order = $this->transport_order_repo
            ->updateTransportOrder($transport_order, json_decode($request->getContent(), true));

        return $this->json($updated_transport_order->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/transport-orders/{id}", name="get_transport_order", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getTransportOrder(int $id): JsonResponse
    {
        $transport_order = $this->transport_order_repo->findOneBy(['id' => $id]);

        if (!$transport_order) {
            throw new NotFoundHttpException('Transport order not found.');
        }

        return $this->json($transport_order->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/transport-orders/{id}", name="delete_transport_order", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function deleteTransportOrder(int $id): JsonResponse
    {
        $transport_order = $this->transport_order_repo->findOneBy(['id' => $id]);

        if (!$transport_order) {
            throw new NotFoundHttpException('Transport order not found.');
        }

        $this->transport_order_repo->removeTransportOrder($transport_order);

        return $this->json($transport_order->toArray(), Response::HTTP_OK);
    }
}
