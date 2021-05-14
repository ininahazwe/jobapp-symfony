<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
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

        for ($i=1; $i<10; $i++) {
            $user = new User();
            $user->setTelephone($faker->numerify('#########'))
                 ->setUpdatedAt($faker->dateTime)
                 ->setCreatedAt($faker->dateTime)
                 ->setRoles(['ROLE_USER'])
                 ->setEmail($faker->unique()->safeEmail)
                 ->setFirstname($faker->firstName)
                 ->setIsTermsClients($faker->randomElement([true, false]))
                 ->setLastConnexionAt($faker->dateTime)
                 ->setUsername($faker->userName)
                 ->setLastname($faker->lastName)
            ;

            $password = $this->encoder->encodePassword($user, 'password');
            $user->setPassword($password);

            $manager->persist($user);

            for ($j=0; $j<1; $j++) {
                $profile = new Profile();
                $profile->setCreatedAt($faker->dateTime)
                        ->setUpdatedAt($faker->dateTime)
                        ->setIsAmenagement($faker->randomElement([true, false]))
                        ->setMetiers('metier')
                        ->setZoneDeRecherche('06')
                        ->setDiplome('diplome')
                        ->setCity($faker->city)
                        ->setIsRqth($faker->randomElement([true, false]))
                        ->setDescription('description')
                        ->setExperiences('experience')
                        ->setCv('cv')
                        ->setBirthdate($faker->dateTime)
                        ->setZipcode($faker->numerify('#####'))
                        ->setIsVisible($faker->randomElement([true, false]))
                        ->setUser($user)
                ;

                $manager->persist($profile);
            }
        }

        $manager->flush();
    }
}