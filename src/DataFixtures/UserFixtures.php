<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const CLIENT_REFERENCE = 'client_';
    public const CLIENT_NB_TUPLE = 20;

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= self::CLIENT_NB_TUPLE; $i++) {
            $user = (new User())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setGuestNumber(random_int(0, 20))
                ->setEmail($faker->unique()->email())
                ->setCreatedAt(new DateTimeImmutable());

            $user->setPassword(
                $this->hasher->hashPassword($user, 'password' . $i)
            );

            $manager->persist($user);

            // **Ajout de la référence pour BookingFixtures**
            $this->addReference(self::CLIENT_REFERENCE . $i, $user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['independent', 'user'];
    }
}
