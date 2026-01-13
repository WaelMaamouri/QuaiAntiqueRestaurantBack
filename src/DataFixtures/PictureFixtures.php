<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Restaurant $restaurant */
        $restaurant = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE, Restaurant::class);

        // Crée 5 pictures pour ce restaurant
        for ($i = 1; $i <= 5; $i++) {
            $picture = (new Picture())
                ->setTitle("Picture $i")
                ->setSlug((new \Symfony\Component\String\Slugger\AsciiSlugger())->slug("Picture $i"))
                ->setCreatedAt(new DateTimeImmutable())
                ->setRestaurant($restaurant);

            $manager->persist($picture);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
        ];
    }
}
