<?php

namespace App\DataFixtures;

use App\Entity\Legume;
use App\Entity\Maraicher;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager, $min=null, $max=null, $nbMaxDecimals=null): void
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

            //Création de legumes lié aux maraichers
            for($j=1; $j<=mt_rand(1,8); $j++)
            {
                $legume = new Legume();
                $legume->setCategorie('Légumes')
                        ->setVariete($faker->word())
                        ->setPrix($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 5))
                        ->setImage('http://via.placeholder.com/90x90')
                        ->setMaraicher($maraicher)
                        ->setQuantite($faker->randomFloat($nbMaxDecimals = 1, $min = 1, $max = 20))
                ;

                $manager->persist($legume);
            }
        }


        $manager->flush();
    }
}
