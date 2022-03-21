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
     * @param array $attributes
     * @return Warehouse
     */
    public function saveWarehouse(array $attributes): Warehouse
    {
        $warehouse = new Warehouse();

        $this->attributesSetter($warehouse, $attributes);

        $this->manager->persist($warehouse);
        $this->manager->flush();

        return $warehouse;
    }

    /**
     * @param Warehouse $warehouse
     * @param array     $attributes
     * @return Warehouse
     */
    public function updateWarehouse(Warehouse $warehouse, array $attributes): Warehouse
    {
        $this->attributesSetter($warehouse, $attributes);
        $this->manager->persist($warehouse);
        $this->manager->flush();

        return $warehouse;
    }

    public function removeWarehouse(Warehouse $warehouse): void
    {
        $this->manager->remove($warehouse);
        $this->manager->flush();
    }

    /**
     * @param Warehouse $warehouse
     * @param array $attributes
     */
    private function attributesSetter(Warehouse $warehouse, array $attributes)
    {
        !array_key_exists('name', $attributes)
            ? true
            : $warehouse->setName($attributes['name']);
        !array_key_exists('description', $attributes)
            ? true
            : $warehouse->setDescription($attributes['description']);
        !array_key_exists('address', $attributes)
            ? true
            : $warehouse->setAddress($attributes['address']);
    }

    /**
     * @param array $ids
     * @return Warehouse[] Returns an array of Warehouse objects
     */
    public function findByIds(array $ids)
    {
        $entities = $this->findBy(array('id' => $ids));
        $data = [];

        foreach ($entities as $entity) {
            $data[] = $entity->toArray();
        }

        return $data;
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
