<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Restaurant;
use App\Entity\User;
use App\DataFixtures\RestaurantFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use Faker\Factory;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public const BOOKING_REFERENCE = 'booking_';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        /** @var Restaurant $restaurant */
        $restaurant = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE, Restaurant::class);

        // Récupérer tous les clients une seule fois
        $clientReferences = [];
        for ($j = 1; $j <= UserFixtures::CLIENT_NB_TUPLE; $j++) {
            $clientReferences[] = $this->getReference(UserFixtures::CLIENT_REFERENCE . $j, User::class);
        }

        // Créer 10 bookings
        for ($i = 1; $i <= 10; $i++) {
            $client = $clientReferences[array_rand($clientReferences)];

            $booking = (new Booking())
                ->setGuestNumber($faker->numberBetween(1, 10))
                ->setOrderDate($faker->dateTimeBetween('now', '+2 months'))
                ->setOrderHour($faker->dateTimeBetween('now', '+2 months'))
                ->setAllergy($faker->optional()->sentence())
                ->setCreatedAt(new DateTimeImmutable())
                ->setRestaurant($restaurant)
                ->setUser($client);

            $manager->persist($booking);

            $this->addReference(self::BOOKING_REFERENCE . $i, $booking);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
            UserFixtures::class,
        ];
    }
}
