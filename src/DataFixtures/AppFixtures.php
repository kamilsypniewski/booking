<?php


namespace App\DataFixtures;

use App\Entity\Apartment;
use App\Entity\Bed;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $apartment = new Apartment();
            $apartment->setName($faker->streetAddress);
            $apartment->setPrice($faker->numberBetween(10, 100) * 100);
            $manager->persist($apartment);

            for ($j = 0; $j < $faker->numberBetween(2, 6); $j++) {
                $bed = new Bed();
                $bed->setName($faker->uuid);
                $bed->setApartment($apartment);
                $manager->persist($bed);
            }
        }
        
        $manager->flush();
    }
}
