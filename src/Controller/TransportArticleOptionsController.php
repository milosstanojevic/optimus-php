<?php


namespace App\Controller;

use App\Repository\RegalPositionRepository;
use App\Repository\RegalRepository;
use App\Repository\WarehouseArticleRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TransportArticleOptionsController extends AbstractController
{
    /**
     * @var RegalRepository
     */
    private $regal_repository;

    /**
     * @var WarehouseRepository
     */
    private $warehouse_repository;

    /**
     * @var RegalPositionRepository
     */
    private $regal_position;

    /**
     * @var WarehouseArticleRepository
     */
    private $warehouse_article;

    public function __construct(
        RegalRepository $regal_repository,
        WarehouseRepository $warehouse_repository,
        RegalPositionRepository $regal_position,
        WarehouseArticleRepository $warehouse_article
    ) {
        $this->regal_repository = $regal_repository;
        $this->warehouse_repository = $warehouse_repository;
        $this->regal_position = $regal_position;
        $this->warehouse_article = $warehouse_article;
    }

    /**
     * @Route("/transport-article-options", name="get_transport_article_options", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getTransportArticleOptions(Request $request): JsonResponse
    {
        $type = $request->query->get('type');
        $article_id = $request->query->get('article_id');
        $warehouse_id = $request->query->get('warehouse_id');
        $regal_id = $request->query->get('regal_id');

        if (!$article_id) {
            throw new NotFoundHttpException('Article ID not provided.');
        }

        if ($type === 'warehouse') {
            $warehouse_articles = $this->warehouse_article->findBy(['article_id' => $article_id]);

            $ids = [];

            foreach ($warehouse_articles as $warehouse_article) {
                $id = $warehouse_article->getWarehouseId();
                if (!in_array($id, $ids)) {
                    $ids[] = $id;
                }
            }

            $warehouses = $this->warehouse_repository->findBy(array('id' => $ids));

            $data = [];

            foreach ($warehouses as $warehouse) {
                $data[] = [
                    'id' => $warehouse->getId(),
                    'name' => $warehouse->getName(),
                ];
            }

            return $this->json($data, Response::HTTP_OK);
        }

        if ($type === 'regal') {
            $warehouse_articles = $this
                ->warehouse_article
                ->findBy(['article_id' => $article_id, 'warehouse_id' => $warehouse_id]);

            $ids = [];

            foreach ($warehouse_articles as $warehouse_article) {
                $id = $warehouse_article->getRegalId();
                if (!in_array($id, $ids)) {
                    $ids[] = $id;
                }
            }

            $regals = $this->regal_repository->findBy(array('id' => $ids));

            $data = [];

            foreach ($regals as $regal) {
                $data[] = [
                    'id' => $regal->getId(),
                    'name' => $regal->getName(),
                ];
            }

            return $this->json($data, Response::HTTP_OK);
        }

        $warehouse_articles = $this
            ->warehouse_article
            ->findBy(['article_id' => $article_id, 'warehouse_id' => $warehouse_id, 'regal_id' => $regal_id]);

        $ids = [];

        foreach ($warehouse_articles as $warehouse_article) {
            $id = $warehouse_article->getRegalPositionId();
            if (!in_array($id, $ids)) {
                $ids[] = $id;
            }
        }

        $regal_positions = $this->regal_position->findBy(array('id' => $ids));

        $data = [];

        foreach ($regal_positions as $regal_position) {
            $data[] = [
                'id' => $regal_position->getId(),
                'name' => $regal_position->getName(),
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }
}
