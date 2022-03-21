<?php

namespace App\Repository;

use App\Entity\RegalPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegalPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegalPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegalPosition[]    findAll()
 * @method RegalPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegalPositionRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, RegalPosition::class);
        $this->manager = $manager;
    }

    /**
     * @param array $data
     * @return RegalPosition
     */
    public function saveRegalPosition(array $data): RegalPosition
    {
        $regal_position = new RegalPosition();

        $regal_position
            ->setName($data['name'])
            ->setRegalId($data['regal_id']);

        $this->manager->persist($regal_position);
        $this->manager->flush();

        return $regal_position;
    }

    /**
     * @param array $ids
     * @return RegalPosition[] Returns an array of RegalPosition objects
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
    //  * @return RegalPosition[] Returns an array of RegalPosition objects
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
    public function findOneBySomeField($value): ?RegalPosition
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
