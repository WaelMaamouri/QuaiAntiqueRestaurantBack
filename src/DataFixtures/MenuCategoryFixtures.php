<?php

namespace App\DataFixtures;

use App\Entity\MenuCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Menu;
use App\Entity\Category;
use App\DataFixtures\MenuFixtures;
use App\DataFixtures\CategoryFixtures;

class MenuCategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {

            // Chaque menu a entre 1 et 3 catégories
            $nbCategories = random_int(1, 3);

            for ($j = 1; $j <= $nbCategories; $j++) {
            $category = $this->getReference( CategoryFixtures::CATEGORY_REFERENCE . random_int(1, 10), Category::class);
            $menu   = $this->getReference(MenuFixtures::MENU_REFERENCE . random_int(1, 10), Menu::class);

                $menuCategory = (new MenuCategory())
                    ->setMenuId($menu)
                    ->setCategoryId($category);

                $manager->persist($menuCategory);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MenuFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
