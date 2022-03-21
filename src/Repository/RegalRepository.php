<?php

namespace App\Repository;

use App\Entity\Regal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Regal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Regal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Regal[]    findAll()
 * @method Regal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegalRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Regal::class);
        $this->manager = $manager;
    }

    /**
     * @param array $data
     * @return Regal
     */
    public function saveRegal(array $data): Regal
    {
        $regal = new Regal();

        $regal
            ->setName($data['name'])
            ->setWarehouseId($data['warehouse_id']);

        $this->manager->persist($regal);
        $this->manager->flush();

        return $regal;
    }

    /**
     * @param array $ids
     * @return Regal[] Returns an array of Regal objects
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
    //  * @return Regal[] Returns an array of Regal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Regal
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
