<?php

namespace App\Tests;

use App\Entity\Legume;
use App\Entity\Maraicher;
use PHPUnit\Framework\TestCase;

class MaraicherUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $maraicher = new Maraicher();

        $maraicher->setEmail('true@test.com')
            ->setPassword('password')
            ->setPrenom('prenom')
            ->setNom('nom')
            ->setVille('testVille')
            ->setEntreprise('entreprise')
            ->setLogo('logo.jpg')
            ->setRoles(['ROLE_MAR']);;

        $this->assertTrue($maraicher->getEmail() === 'true@test.com');
        $this->assertTrue($maraicher->getPassword() === 'password');
        $this->assertTrue($maraicher->getPrenom() === 'prenom');
        $this->assertTrue($maraicher->getNom() === 'nom');
        $this->assertTrue($maraicher->getVille() === 'testVille');
        $this->assertTrue($maraicher->getEntreprise() === 'entreprise');
        $this->assertTrue($maraicher->getLogo() === 'logo.jpg');
        $this->assertTrue($maraicher->getRoles() === ['ROLE_MAR']);
    }


    public function testIsFalse(): void
    {
        $maraicher = new Maraicher();

        $maraicher->setEmail('true@test.com')
            ->setPassword('password')
            ->setPrenom('prenom')
            ->setNom('nom')
            ->setVille('testVille')
            ->setEntreprise('entreprise')
            ->setLogo('logo.jpg')
            ->setRoles(['ROLE_MAR']);

            $this->assertFalse($maraicher->getEmail() === 'false@test.com');
            $this->assertFalse($maraicher->getPassword() === 'false');
            $this->assertFalse($maraicher->getPrenom() === 'false');
            $this->assertFalse($maraicher->getNom() === 'false');
            $this->assertFalse($maraicher->getVille() === 'false');
            $this->assertFalse($maraicher->getEntreprise() === 'false');
            $this->assertFalse($maraicher->getLogo() === 'false.jpg');
            $this->assertFalse($maraicher->getRoles() === ['ROLE_USER']);
    }

    public function testIsEmpty()
    {
        $maraicher = new Maraicher();

        $this->assertEmpty($maraicher->getEmail());
        $this->assertEmpty($maraicher->getPassword());
        $this->assertEmpty($maraicher->getPrenom());
        $this->assertEmpty($maraicher->getNom());
        $this->assertEmpty($maraicher->getVille());
        $this->assertEmpty($maraicher->getEntreprise());
        $this->assertEmpty($maraicher->getLogo());
        $this->assertEmpty($maraicher->getId());
        $this->assertEmpty($maraicher->getUserIdentifier());

    }

    public function testGetAddRemoveLegume()
    {
        $maraicher = new Maraicher();
        $legume = new Legume();

        //v??rifie si il n'a aucun l??gumes associ?? ?? un maraicher
        $this->assertEmpty($maraicher->getLegumes());

        //Ajoute un l??gume au maraicher
        //V??rifie qu'on r??cup??re bien le l??gume en question
        $maraicher->addLegume($legume);
        $this->assertContains($legume, $maraicher->getLegumes());

        //Remove un l??gume
        //V??rifie que le l??gume dans le maraicher est vide
        $maraicher->removeLegume($legume);
        $this->assertEmpty($maraicher->getLegumes());
    }
}
