<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture
{
    public const RESTAURANT_REFERENCE = 'restaurant';

    public function load(ObjectManager $manager): void
    {
        // Crée un restaurant
        $restaurant = (new Restaurant())
            ->setName("Quai Antique")
            ->setDescription("Le restaurant Au Quai Antique est un nouvel établissement situé au cœur de Chambéry. Il propose une cuisine traditionnelle savoyarde, mettant en valeur des ingrédients locaux et de saison pour créer des plats savoureux et authentiques. Le chef Arnaud Michant, qui a été formé dans les meilleurs restaurants de la région, dirige l'établissement. La carte du restaurant est régulièrement mise à jour afin de mettre en avant les meilleurs produits de la saison. L'équipe de service est amicale et attentionnée, offrant ainsi une expérience agréable aux clients.")
            ->setAmOpeningTime([])
            ->setPmOpeningTime([])
            ->setMaxGuest(50)
            ->setCreatedAt(new DateTimeImmutable());

        $manager->persist($restaurant);

        // Ajouter la référence pour pouvoir y accéder depuis PictureFixtures
        $this->addReference(self::RESTAURANT_REFERENCE, $restaurant);

        $manager->flush();
    }
}
