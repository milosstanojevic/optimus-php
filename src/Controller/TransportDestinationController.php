<?php

namespace App\Controller;

use App\Repository\TransportDestinationRepository;
use App\Repository\TransportRouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TransportDestinationController extends AbstractController
{
    private $transport_destination_repository;
    private $transport_route_repository;

    public function __construct(
        TransportDestinationRepository $transport_destination_repository,
        TransportRouteRepository $transport_route_repository
    )
    {
        $this->transport_destination_repository = $transport_destination_repository;
        $this->transport_route_repository = $transport_route_repository;
    }

    /**
     * @Route("/transport-route/{id}/destinations", name="get_all_transport_destinations", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getAll(int $id): JsonResponse
    {
        $transport = $this->transport_route_repository->findOneBy(['id' => $id]);

        if (!$transport) {
            throw new NotFoundHttpException('Transport not found.');
        }

        $destinations = $this->transport_destination_repository->findBy(['transport_id' => $id]);
        $data = [];

        foreach ($destinations as $destination) {
            $data[] = $destination->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/transport-route/{id}/destination/{destination_id}", name="get_transport_destination", methods={"GET"})
     * @param int $id
     * @param int $destination_id
     * @return JsonResponse
     */
    public function getDestination(int $id, int $destination_id): JsonResponse
    {
        $transport = $this->transport_route_repository->findOneBy(['id' => $id]);

        if (!$transport) {
            throw new NotFoundHttpException('Transport not found.');
        }

        $destination = $this->transport_destination_repository->findOneBy(['id' => $destination_id]);

        if (!$destination) {
            throw new NotFoundHttpException('Destination not found.');
        }


        return $this->json($destination->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/transport-route/{id}/destinations", name="add_transport_destination", methods={"POST"})
     * @param int     $id
     * @param Request $request
     * @return JsonResponse
     */
    public function add(int $id, Request $request): JsonResponse
    {
        $transport = $this->transport_route_repository->findOneBy(['id' => $id]);

        if (!$transport) {
            throw new NotFoundHttpException('Transport not found.');
        }

        $data = json_decode($request->getContent(), true);

        if (
            empty($data) || !array_key_exists('parent_id', $data)
        ) {
            throw new UnprocessableEntityHttpException('Parent id required');
        }

        $regal_position = $this->transport_destination_repository->saveTransportDestination([
            'transport_id' => $id,
            'parent_id' => $data['parent_id'],
            'parent' => $data['parent'],
        ]);

        return $this->json($regal_position->toArray(), Response::HTTP_CREATED);
    }
}
