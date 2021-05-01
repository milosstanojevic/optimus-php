<?php


namespace App\Controller;

use App\Repository\RegalPositionRepository;
use App\Repository\RegalRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegalPositionController extends AbstractController
{
    /**
     * @var RegalRepository
     */
    private $regal_repository;

    /**
     * @var RegalPositionRepository
     */
    private $regal_position_repository;

    public function __construct(RegalRepository $regal_repository, RegalPositionRepository $regal_position_repository)
    {
        $this->regal_repository = $regal_repository;
        $this->regal_position_repository = $regal_position_repository;
    }

    /**
     * @Route("/regals/{id}/positions", name="get_regal_positions", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getRegalPositions(int $id): JsonResponse
    {
        $regal = $this->regal_repository->findOneBy(['id' => $id]);

        if (!$regal) {
            throw new NotFoundHttpException('Regal not found.');
        }

        $positions = $this->regal_position_repository->findBy(['regal_id' => $id]);

        $data = [];

        foreach ($positions as $position) {
            $data[] = $position->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/regals/{id}/positions", name="add_regal_position", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function add(int $id, Request $request): JsonResponse
    {
        $regal = $this->regal_repository->findOneBy(['id' => $id]);

        if (!$regal) {
            throw new NotFoundHttpException('Regal not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('name', $data)) {
            throw new UnprocessableEntityHttpException('Name required');
        }

        $regal_position = $this->regal_position_repository->saveRegalPosition([
            'name' => $data['name'],
            'regal_id' => $id,
        ]);

        return $this->json($regal_position->toArray(), Response::HTTP_CREATED);
    }
}
