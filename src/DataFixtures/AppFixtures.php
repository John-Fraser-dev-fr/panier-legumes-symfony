<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Entity\Legume;
use App\Entity\Maraicher;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager, $min = null, $max = null, $nbMaxDecimals = null): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Création de maraicher
        for ($i = 1; $i <= 9; $i++) {
            $maraicher = new Maraicher();
            $maraicher->setEmail($faker->email())
                ->setPassword('testtest')
                ->setPrenom($faker->firstName())
                ->setNom($faker->lastName())
                ->setVille($faker->city())
                ->setEntreprise($faker->company())
                ->setLogo('http://via.placeholder.com/90x90');

            $manager->persist($maraicher);

            //Création de legumes lié aux maraichers
            for ($j = 1; $j <= mt_rand(1, 8); $j++) {
                $legume = new Legume();
                $legume->setCategorie('Légumes')
                    ->setVariete($faker->word())
                    ->setPrix($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 5))
                    ->setImage('http://via.placeholder.com/90x90')
                    ->setMaraicher($maraicher)
                    ->setQuantite($faker->randomFloat($nbMaxDecimals = 1, $min = 1, $max = 20));

                $manager->persist($legume);
            }
        }

        //Création d'utilisateur
        for ($i = 1; $i <= 9; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword('testtest')
                ->setPrenom($faker->firstName())
                ->setNom($faker->lastName())
                ->setPhone($faker->phoneNumber())
                ->setNRue($faker->numberBetween($min = 1, $max = 98))
                ->setRue($faker->streetName())
                ->setCdPostal($faker->numberBetween($min = 12538, $max = 87290))
                ->setVille($faker->city());

            $manager->persist($user);

            
  
        }

        //Création de commande lié a user
        for ($i = 1; $i <= 3; $i++) {
            $commande = new Commande();
            $commande->setUser($user)
                ->setMontant($faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 100))
                ->setStatus($faker->numberBetween($min = 0, $max = 3))
                ->setDate($faker->dateTimeBetween('-6 months'));

            $manager->persist($commande);

            //Création details commande lié à la commande
            for ($i = 1; $i <= 3; $i++) {
                $detailsCommande = new DetailsCommande();
                $detailsCommande->setCommande($commande)
                    ->setQuantite($faker->numberBetween($min = 1, $max = 5))
                    ->setPrix($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 5))
                    ->setLegume($legume);

                $manager->persist($detailsCommande);
            }
        }

        $manager->flush();
    }

    
}
