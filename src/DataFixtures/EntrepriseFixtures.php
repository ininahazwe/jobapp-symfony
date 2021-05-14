<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use App\Entity\EntrepriseOffre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EntrepriseFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //Utilisation de Faker
        $faker = Factory::create('fr_FR');

        // création de 1à utilisateurs avec un profil chacun

        for ($i = 0; $i < 10; $i++) {
            $entreprise[$i] = new Entreprise();
            $entreprise[$i]->setCreatedAt($faker->dateTime);
            $entreprise[$i]->setUpdatedAt($faker->dateTime);
            $entreprise[$i]->setName($faker->name);
            $entreprise[$i]->setDescription($faker->text());
            $entreprise[$i]->setLogo($faker->name);
            $entreprise[$i]->setAddress($faker->address);
            $entreprise[$i]->setCity($faker->city);
            $entreprise[$i]->setZipcode($faker->postcode);
            $entreprise[$i]->setSecteur($faker->text);
            $entreprise[$i]->setSlug($faker->name);

            $manager->persist($entreprise[$i]);

            for ($j = 0; $j < 10; $j++) {
                $offre[$j] = new EntrepriseOffre();
                $offre[$j]->setCreatedAt($faker->dateTime);
                $offre[$j]->setUpdatedAt($faker->dateTime);
                $offre[$j]->setFormule($faker->name);
                $offre[$j]->setNombreOffres($faker->numerify('##'));
                $offre[$j]->setDebutContratAt($faker->dateTime);
                $offre[$j]->setFinContratAt($faker->dateTime);
                $offre[$j]->setIsCvTheque($faker->randomElement([true, false]));
                $offre[$j]->setPrix($faker->numerify('#########'));
                $offre[$j]->setFacture($faker->numerify('#########'));
                $offre[$j]->setEntreprise($entreprise);

                $manager->persist($offre[$i]);
            }
        }

        $manager->flush();
    }
}