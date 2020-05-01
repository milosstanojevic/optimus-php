<?php

namespace App\DataFixtures;

use App\Entity\Warehouse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 10; $i++) {
            $warehouse = new Warehouse();
            $warehouse->setName("Warehouse $i");
            $warehouse->setDescription("Warehouse desc $i");
            $warehouse->setAddress("address $i");
            $manager->persist($warehouse);
        }

        $manager->flush();
    }
}
