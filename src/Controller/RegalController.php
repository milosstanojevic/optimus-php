<?php


namespace App\Controller;

use App\Repository\RegalRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegalController
{
    /**
     * @var RegalRepository
     */
    private $regalRepository;

    /**
     * @var WarehouseRepository
     */
    private $warehouseRepository;

    public function __construct(RegalRepository $regalRepository, WarehouseRepository $warehouseRepository)
    {
        $this->regalRepository = $regalRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * @Route("/warehouses/{id}/regals", name="get_warehouse_regals", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getWarehouseRegals($id): JsonResponse
    {
        $warehouse = $this->warehouseRepository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $regals = $this->regalRepository->findBy(['warehouse_id' => $id]);

        $data = [];

        foreach ($regals as $regal) {
            $data[] = $regal->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
