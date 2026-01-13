<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\Restaurant;
use App\DataFixtures\RestaurantFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use Faker;

class MenuFixtures extends Fixture implements DependentFixtureInterface
{
    public const MENU_REFERENCE = 'menu_';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        /** @var Restaurant $restaurant */
        $restaurant = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE, Restaurant::class);

        // Crée 10 menus pour ce restaurant
        for ($i = 1; $i <= 10; $i++) {
            $menu = (new Menu())
                ->setTitle("Menu " . $faker->word())
                ->setDescription($faker->sentence())
                ->setPrice($faker->randomFloat(2, 15, 80))
                ->setRestaurant($restaurant)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($menu);

            $this->addReference(self::MENU_REFERENCE . $i, $menu);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class
        ];
    }
}
