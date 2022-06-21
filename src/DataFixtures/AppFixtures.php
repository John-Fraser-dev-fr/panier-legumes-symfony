<?php

namespace App\DataFixtures;

use App\Entity\Maraicher;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager, $min=null, $max=null): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Création d'utilisateur
        for($i=1;$i<=9;$i++)
        {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword('testtest')
                ->setPrenom($faker->firstName())
                ->setNom($faker->lastName())
                ->setPhone($faker->phoneNumber())
                ->setNRue($faker->numberBetween($min= 1, $max=98))
                ->setRue($faker->streetName())
                ->setCdPostal($faker->numberBetween($min=12538, $max=87290))
                ->setVille($faker->city())
            ;

            $manager->persist($user);
        }


        //Création de maraicher
        for($i=1;$i<=9;$i++)
        {
            $maraicher = new Maraicher();
            $maraicher->setEmail($faker->email())
                    ->setPassword('testtest')
                    ->setPrenom($faker->firstName())
                    ->setNom($faker->lastName())
                    ->setVille($faker->city())
                    ->setEntreprise($faker->company())
                    ->setLogo('http://via.placeholder.com/90x90')
            ;

            $manager->persist($maraicher);
        }


        $manager->flush();
    }
}
