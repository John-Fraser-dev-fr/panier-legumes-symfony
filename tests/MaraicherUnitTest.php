<?php

namespace App\Tests;


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
            ->setLogo('logo.jpg');

        $this->assertTrue($maraicher->getEmail() === 'true@test.com');
        $this->assertTrue($maraicher->getPassword() === 'password');
        $this->assertTrue($maraicher->getPrenom() === 'prenom');
        $this->assertTrue($maraicher->getNom() === 'nom');
        $this->assertTrue($maraicher->getVille() === 'testVille');
        $this->assertTrue($maraicher->getEntreprise() === 'entreprise');
        $this->assertTrue($maraicher->getLogo() === 'logo.jpg');
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
            ->setLogo('logo.jpg');

            $this->assertFalse($maraicher->getEmail() === 'false@test.com');
            $this->assertFalse($maraicher->getPassword() === 'false');
            $this->assertFalse($maraicher->getPrenom() === 'false');
            $this->assertFalse($maraicher->getNom() === 'false');
            $this->assertFalse($maraicher->getVille() === 'false');
            $this->assertFalse($maraicher->getEntreprise() === 'false');
            $this->assertFalse($maraicher->getLogo() === 'false.jpg');
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
    }
}
