<?php

namespace App\Repository;

use App\Entity\TransportOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransportOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportOrder[]    findAll()
 * @method TransportOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportOrderRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, TransportOrder::class);
        $this->manager = $manager;
    }

    public function saveTransportOrder(array $attributes): TransportOrder
    {
        $transport_order = new TransportOrder();

        $transport_order
            ->setParent($attributes['parent'])
            ->setParentId($attributes['parent_id'])
            ->setStatus(1)
            ->setTransportId(array_key_exists('transport_id', $attributes) ? $attributes['transport_id'] : null);

        $this->manager->persist($transport_order);
        $this->manager->flush();

        return $transport_order;
    }

    /**
     * @param TransportOrder $transport_order
     * @param array          $attributes
     * @return TransportOrder
     */
    public function updateTransportOrder(TransportOrder $transport_order, array $attributes): TransportOrder
    {
        empty($attributes['parent']) ? true : $transport_order->setParent($attributes['parent']);
        empty($attributes['parent_id']) ? true : $transport_order->setParentId($attributes['parent_id']);
        empty($attributes['transport_id']) ? true : $transport_order->setTransportId($attributes['transport_id']);
        empty($attributes['status']) ? true : $transport_order->setStatus($attributes['status']);

        $this->manager->persist($transport_order);
        $this->manager->flush();

        return $transport_order;
    }

    public function removeTransportOrder(TransportOrder $transport_order)
    {
        $this->manager->remove($transport_order);
        $this->manager->flush();
    }

    // /**
    //  * @return TransportOrder[] Returns an array of TransportOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransportOrder
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
