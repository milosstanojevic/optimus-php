<?php


namespace App\Controller;

use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WarehouseController extends AbstractController
{
    private $warehouse_repository;

    public function __construct(WarehouseRepository $warehouse_repository)
    {
        $this->warehouse_repository = $warehouse_repository;
    }

    /**
     * @Route("/warehouses", name="get_all_warehouses", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $warehouses = $this->warehouse_repository->findAll();
        $data = [];

        foreach ($warehouses as $warehouse) {
            $data[] = $warehouse->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses", name="add_warehouse", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('name', $data)) {
            throw new UnprocessableEntityHttpException('Name required');
        }

        $warehouse = $this->warehouse_repository->saveWarehouse($data);

        return $this->json($warehouse->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/warehouses/{id}", name="update_warehouse", methods={"PUT"})
     * @param  int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        empty($data['name']) ? true : $warehouse->setName($data['name']);
        empty($data['description']) ? true : $warehouse->setDescription($data['description']);
        empty($data['address']) ? true : $warehouse->setAddress($data['address']);

        $updatedWarehouse = $this->warehouse_repository->updateWarehouse($warehouse);

        return $this->json($updatedWarehouse->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}", name="get_one_warehouse", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getWarehouse(int $id): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        return $this->json($warehouse->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}", name="delete_warehouse", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $this->warehouse_repository->removeWarehouse($warehouse);

        return $this->json(['status' => 'Warehouse deleted'], Response::HTTP_NO_CONTENT);
    }
}
