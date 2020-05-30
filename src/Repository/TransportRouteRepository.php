<?php

namespace App\Repository;

use App\Entity\TransportRoute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransportRoute|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportRoute|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportRoute[]    findAll()
 * @method TransportRoute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportRouteRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, TransportRoute::class);
        $this->manager = $manager;
    }

    /**
     * @param array $data
     * @return TransportRoute
     */
    public function saveTransportRoute(array $data)
    {
        $route = new TransportRoute();

        $route
            ->setName($data['name'])
            ->setDescription($data['description']);

        $this->manager->persist($route);
        $this->manager->flush();

        return $route;
    }

    /**
     * @param TransportRoute $route
     * @return TransportRoute
     */
    public function updateTransportRoute(TransportRoute $route): TransportRoute
    {
        $this->manager->persist($route);
        $this->manager->flush();

        return $route;
    }

    // /**
    //  * @return TransportRoute[] Returns an array of TransportRoute objects
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
    public function findOneBySomeField($value): ?TransportRoute
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
