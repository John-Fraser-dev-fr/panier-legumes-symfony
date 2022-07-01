<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Commande;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();

        $user->setEmail('true@test.com')
             ->setPassword('password')
             ->setPrenom('prenom')
             ->setNom('nom')
             ->setPhone('0123456789')
             ->setNRue(1)
             ->setRue('rue du test')
             ->setCdPostal('12345')
             ->setVille('testVille')
             ->setRoles(['ROLE_USER'])
             ->__toString('email@gmail.com');

        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getPrenom() === 'prenom');
        $this->assertTrue($user->getNom()=== 'nom');
        $this->assertTrue($user->getPhone() === '0123456789');
        $this->assertTrue($user->getNRue() === 1);
        $this->assertTrue($user->getRue() === 'rue du test');
        $this->assertTrue($user->getCdPostal() === '12345');
        $this->assertTrue($user->getVille() === 'testVille');  
        $this->assertTrue($user->getRoles() === ['ROLE_USER']); 
        $this->assertTrue($user->__toString() === $user->getEmail());
    }


    public function testIsFalse(): void
    {
        $user = new User();

        $user->setEmail('true@test.com')
             ->setPassword('password')
             ->setPrenom('prenom')
             ->setNom('nom')
             ->setPhone('0123456789')
             ->setNRue('1')
             ->setRue('rue du test')
             ->setCdPostal('12345')
             ->setVille('testVille')
             ->setRoles(['ROLE_USER'])
             ->__toString('email@gmail.com');

        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->getPrenom() === 'false');
        $this->assertFalse($user->getNom()=== 'false');
        $this->assertFalse($user->getPhone() === '9876543210');
        $this->assertFalse($user->getNRue() === '9');
        $this->assertFalse($user->getRue() === 'false');
        $this->assertFalse($user->getCdPostal() === '54321');
        $this->assertFalse($user->getVille() === 'false');
        $this->assertFalse($user->getRoles() === ['ROLE_MAR']);
        $this->assertFalse($user->__toString() === 'false@gmail.com');
        
    }

    public function testIsEmpty()
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getPrenom());
        $this->assertEmpty($user->getNom());
        $this->assertEmpty($user->getPhone());
        $this->assertEmpty($user->getNRue());
        $this->assertEmpty($user->getRue());
        $this->assertEmpty($user->getCdPostal());
        $this->assertEmpty($user->getVille());
        $this->assertEmpty($user->getId());
        $this->assertEmpty($user->getUserIdentifier());
    }

    public function testAddGetRemoveCommande()
    {
        $user = new User();
        $commande = new Commande();
        

        //Vérifie si il n'y a aucunes commande associé à user
        $this->assertEmpty($user->getCommandes());

        //Ajoute une commande à user
        //Vérifie qu'on récupére bien la commande
        $user->addCommande($commande);
        $this->assertContains($commande, $user->getCommandes());

        //Remove la commande
        //Vérifie que user n'a plus de commande
        $user->removeCommande($commande);
        $this->assertEmpty($user->getCommandes());
    }
}
