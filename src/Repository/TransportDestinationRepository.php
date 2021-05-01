<?php

namespace App\Repository;

use App\Entity\TransportDestination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransportDestination|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportDestination|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportDestination[]    findAll()
 * @method TransportDestination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportDestinationRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, TransportDestination::class);
        $this->manager = $manager;
    }

    public function saveTransportDestination(array $data): TransportDestination {
        $destination = new TransportDestination();

        $destination
            ->setParentId($data['parent_id'])
            ->setParent($data['parent'])
            ->setTransportId($data['transport_id']);

        $this->manager->persist($destination);
        $this->manager->flush();

        return $destination;
    }

    // /**
    //  * @return TransportDestination[] Returns an array of TransportDestination objects
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
    public function findOneBySomeField($value): ?TransportDestination
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
