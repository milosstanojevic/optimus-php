<?php


namespace App\Controller;

use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WarehouseController extends AbstractController
{
    private $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * @Route("/warehouses", name="get_all_warehouses", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $warehouses = $this->warehouseRepository->findAll();
        $data = [];

        foreach ($warehouses as $warehouse) {
            $data[] = $warehouse->toArray();
        }

        return $this->json($data, Response::HTTP_OK,   ['content-type' => 'application/json']);
    }

    /**
     * @Route("/warehouses", name="add_warehouses", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $warehouse = $this->warehouseRepository->saveWarehouse($data);

        return new JsonResponse($warehouse->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/warehouses/{id}", name="update_warehouse", methods={"PUT"})
     * @param  int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $warehouse = $this->warehouseRepository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        empty($data['name']) ? true : $warehouse->setName($data['name']);
        empty($data['description']) ? true : $warehouse->setDescription($data['description']);
        empty($data['address']) ? true : $warehouse->setAddress($data['address']);

        $updatedWarehouse = $this->warehouseRepository->updateWarehouse($warehouse);

        return new JsonResponse($updatedWarehouse->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}", name="get_one_warehouse", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getWarehouse($id): JsonResponse
    {
        $warehouse = $this->warehouseRepository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        return new JsonResponse($warehouse->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}", name="delete_warehouse", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $warehouse = $this->warehouseRepository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $this->warehouseRepository->removeWarehouse($warehouse);

        return new JsonResponse(['status' => 'Warehouse deleted'], Response::HTTP_NO_CONTENT);
    }
}
