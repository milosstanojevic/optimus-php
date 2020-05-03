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
use Symfony\Component\Routing\Annotation\Route;

class RegalPositionController
{
    /**
     * @var RegalRepository
     */
    private $regalRepository;

    /**
     * @var RegalPositionRepository
     */
    private $regalPositionRepository;

    public function __construct(RegalRepository $regalRepository, RegalPositionRepository $regalPositionRepository)
    {
        $this->regalRepository = $regalRepository;
        $this->regalPositionRepository = $regalPositionRepository;
    }

    /**
     * @Route("/regals/{id}/positions", name="get_regal_positions", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getRegalPositions($id): JsonResponse
    {
        $regal = $this->regalRepository->findOneBy(['id' => $id]);

        if (!$regal) {
            throw new NotFoundHttpException('Regal not found.');
        }

        $positions = $this->regalPositionRepository->findBy(['regal_id' => $id]);

        $data = [];

        foreach ($positions as $position) {
            $data[] = $position->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
