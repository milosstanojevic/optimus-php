<?php

namespace App\Repository;

use App\Entity\Warehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Warehouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warehouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warehouse[]    findAll()
 * @method Warehouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Warehouse::class);
        $this->manager = $manager;
    }

    /**
     * @param array $data
     * @return Warehouse
     */
    public function saveWarehouse(array $data): Warehouse
    {
        $warehouse = new Warehouse();

        $warehouse
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setAddress($data['address']);

        $this->manager->persist($warehouse);
        $this->manager->flush();

        return $warehouse;
    }

    /**
     * @param Warehouse $warehouse
     * @return Warehouse
     */
    public function updateWarehouse(Warehouse $warehouse): Warehouse
    {
        $this->manager->persist($warehouse);
        $this->manager->flush();

        return $warehouse;
    }

    public function removeWarehouse(Warehouse $warehouse)
    {
        $this->manager->remove($warehouse);
        $this->manager->flush();
    }

    // /**
    //  * @return Warehouse[] Returns an array of Warehouse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Warehouse
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
