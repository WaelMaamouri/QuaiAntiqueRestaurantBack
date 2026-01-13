<?php

namespace App\DataFixtures;

use App\Entity\Food;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use DateTimeImmutable;

class FoodFixtures extends Fixture
{
    public const FOOD_REFERENCE = 'food_';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 30; $i++) {
            $food = (new Food())
                ->setTitle($faker->words(2, true))
                ->setDescription($faker->sentence())
                ->setPrice($faker->randomFloat(2, 5, 50))
                ->setCreatedAt(new DateTimeImmutable());


            $manager->persist($food);

            $this->addReference(self::FOOD_REFERENCE . $i, $food);
        }

        $manager->flush();
    }
}
