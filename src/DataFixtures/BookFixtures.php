<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use App\Repository\CategoryRepository;

class BookFixtures extends Fixture
{

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create();

        for ($i = 0; $i < 30 ; $i++) {

            $randomCategory = rand(1,5);
            $category = $this->categoryRepository->find($randomCategory);


            $book = new Book();
            $book->setAuthor($faker->name())
                ->setAvailability(1)
                ->setCategory($category)
                ->setReleaseDate($faker->dateTime($max = 'now', $timezone = null))
                ->setSummary($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setTitle($faker->sentence($nbWords = 3, $variableNbWords = true))
            ;
            $manager->persist($book);
        }

        $manager->flush();
    }
}
