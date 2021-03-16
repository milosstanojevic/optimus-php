<?php

namespace App\Controller;

use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MerchantController extends AbstractController
{
    private $merchant_repository;

    public function __construct(MerchantRepository $merchant_repository)
    {
        $this->merchant_repository = $merchant_repository;
    }

    /**
     * @Route("/merchants", name="get_all_merchants", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $merchants = $this->merchant_repository->findAll();
        $data = [];

        foreach ($merchants as $merchant) {
            $data[] = $merchant->toArray();
        }

        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/merchants", name="add_merchant", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data) && !array_key_exists('name', $data)) {
            throw new UnprocessableEntityHttpException('Name required');
        }

        $merchant = $this->merchant_repository->saveMerchant($data);

        return $this->json($merchant->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/merchants/{id}", name="update_merchant", methods={"PUT"})
     * @param  int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $merchant = $this->merchant_repository->findOneBy(['id' => $id]);

        if (!$merchant) {
            throw new NotFoundHttpException('Merchant not found.');
        }

        empty($data['name']) ? true : $merchant->setName($data['name']);
        empty($data['description']) ? true : $merchant->setDescription($data['description']);
        empty($data['address']) ? true : $merchant->setAddress($data['address']);

        $updatedMerchant = $this->merchant_repository->updateMerchant($merchant);

        return $this->json($updatedMerchant->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/merchants/{id}", name="get_one_merchant", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getMerchant(int $id): JsonResponse
    {
        $merchant = $this->merchant_repository->findOneBy(['id' => $id]);

        if (!$merchant) {
            throw new NotFoundHttpException('Merchant not found.');
        }

        return $this->json($merchant->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/merchants/{id}", name="delete_merchants", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $merchant = $this->merchant_repository->findOneBy(['id' => $id]);

        if (!$merchant) {
            throw new NotFoundHttpException('Merchant not found.');
        }

        $this->merchant_repository->removeMerchant($merchant);

        return $this->json(['status' => 'Merchant deleted'], Response::HTTP_NO_CONTENT);
    }
}
