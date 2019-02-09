<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = ['Bande Dessinée', 'Polar', 'Thriller', 'Amour', 'Science Fiction'];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
