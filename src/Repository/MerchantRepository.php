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
     * @param array $attributes
     * @return Merchant
     */
    public function saveMerchant(array $attributes): Merchant
    {
        $merchant = new Merchant();

        $this->attributesSetter($merchant, $attributes);

        $this->manager->persist($merchant);
        $this->manager->flush();

        return $merchant;
    }

    /**
     * @param Merchant $merchant
     * @param array    $attributes
     * @return Merchant
     */
    public function updateMerchant(Merchant $merchant, array $attributes): Merchant
    {
        $this->attributesSetter($merchant, $attributes);
        $this->manager->persist($merchant);
        $this->manager->flush();

        return $merchant;
    }

    public function removeMerchant(Merchant $merchant): void
    {
        $this->manager->remove($merchant);
        $this->manager->flush();
    }

    /**
     * @param Merchant $merchant
     * @param array $attributes
     */
    private function attributesSetter(Merchant $merchant, array $attributes)
    {
        !array_key_exists('name', $attributes)
            ? true
            : $merchant->setName($attributes['name']);
        !array_key_exists('description', $attributes)
            ? true
            : $merchant->setDescription($attributes['description']);
        !array_key_exists('address', $attributes)
            ? true
            : $merchant->setAddress($attributes['address']);
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
