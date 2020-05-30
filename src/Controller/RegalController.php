<?php


namespace App\Controller;

use App\Repository\RegalRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegalController extends AbstractController
{
    /**
     * @var RegalRepository
     */
    private $regal_repository;

    /**
     * @var WarehouseRepository
     */
    private $warehouse_repository;

    public function __construct(RegalRepository $regal_repository, WarehouseRepository $warehouse_repository)
    {
        $this->regal_repository = $regal_repository;
        $this->warehouse_repository = $warehouse_repository;
    }

    /**
     * @Route("/warehouses/{id}/regals", name="get_warehouse_regals", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getWarehouseRegals($id): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $regals = $this->regal_repository->findBy(['warehouse_id' => $id]);

        $data = [];

        foreach ($regals as $regal) {
            $data[] = $regal->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/warehouses/{id}/regals", name="add_warehouse_regal", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function add(int $id, Request $request): JsonResponse
    {
        $warehouse = $this->warehouse_repository->findOneBy(['id' => $id]);

        if (!$warehouse) {
            throw new NotFoundHttpException('Warehouse not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('name', $data)) {
            throw new UnprocessableEntityHttpException('Name required');
        }

        $warehouse_regal = $this->regal_repository->saveRegal([
            'warehouse_id' => $id,
            'name' => $data['name']
        ]);

        return $this->json($warehouse_regal->toArray(), Response::HTTP_CREATED);
    }
}
