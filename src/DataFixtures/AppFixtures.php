<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++){
            $user = new User();
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setEmail($user->getFirstName() . '.' . $user->getLastName() . '@gmail.com');
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));

            $manager->persist($user);
        }

        for ($i = 1; $i <= 9; $i++){
            $product = new Product();
            $product->setName('Article n°' . $i);
            $product->setShortDescription('Petite description de l\'article n°' . $i);
            $product->setFullDescription($i . ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce et nisl eget mi cursus auctor nec ut urna. Cras laoreet consectetur bibendum. Proin lobortis at felis vitae elementum. Morbi vitae leo ac turpis fermentum ultricies ac sit amet diam. Sed condimentum magna in lectus semper placerat. Donec dapibus libero ex, quis commodo velit viverra vel. Pellentesque ac risus ultricies, aliquet quam quis, interdum velit. Nunc sed consequat nunc.');
            $product->setPrice($faker->randomFloat(2, 5, 200));
            $product->setPicture('Article' . $i . '.webp');

            $manager->persist($product);
        }

        $manager->flush();
    }
}
