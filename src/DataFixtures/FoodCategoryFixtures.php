<?php

namespace App\DataFixtures;

use App\Entity\FoodCategory;
use App\Entity\Food;
use App\Entity\Category;
use App\Service\Utils;
use App\Entity\Picture;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;
use App\DataFixtures\RestaurantFixtures;





class FoodCategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 30; $i++) {

            // Chaque food a entre 1 et 3 catégories
            $nbCategories = random_int(1, 3);

            for ($j = 1; $j <= $nbCategories; $j++) {
            /** @var Restaurant $restaurant */
            $category = $this->getReference( CategoryFixtures::CATEGORY_REFERENCE . random_int(1, 10), Category::class);
            $food     = $this->getReference(FoodFixtures::FOOD_REFERENCE . random_int(1, 30), Food::class);


                $foodCategory = (new FoodCategory())
                    ->setCategoryId($category)
                    ->setFoodId($food);

                $manager->persist($foodCategory);
            }
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            FoodFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
