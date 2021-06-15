<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnnonceFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i <= 50; $i++) {
            $annonce = new Annonce();
            $user = $manager->getRepository(User::class)->findAll();
            $annonce
                ->setName($faker->words(2, true))
                ->setDescription($faker->sentences(3, true))
                ->setIsActive($faker->randomElement(true, false))
                ->setAuthor($faker->randomElement($user));

            $manager->persist($annonce);
        }
        $manager->flush();
    }
}