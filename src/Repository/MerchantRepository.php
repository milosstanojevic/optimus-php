<?php

namespace App\Repository;

use App\Entity\Merchant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Merchant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Merchant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Merchant[]    findAll()
 * @method Merchant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Merchant::class);
        $this->manager = $manager;
    }

    /**
     * @param array $data
     * @return Merchant
     */
    public function saveMerchant(array $data): Merchant
    {
        $merchant = new Merchant();

        $merchant
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setAddress($data['address']);

        $this->manager->persist($merchant);
        $this->manager->flush();

        return $merchant;
    }

    /**
     * @param Merchant $merchant
     * @return Merchant
     */
    public function updateMerchant(Merchant $merchant): Merchant
    {
        $this->manager->persist($merchant);
        $this->manager->flush();

        return $merchant;
    }

    public function removeMerchant(Merchant $merchant)
    {
        $this->manager->remove($merchant);
        $this->manager->flush();
    }

    // /**
    //  * @return Merchant[] Returns an array of Merchant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Merchant
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
