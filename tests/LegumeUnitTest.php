<?php

namespace App\Tests;

use App\Entity\Legume;
use App\Entity\Maraicher;
use PHPUnit\Framework\TestCase;

class LegumeUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $legume = new Legume();
        $maraicher = new Maraicher();

        $legume->setCategorie('légumes')
            ->setVariete('tomate')
            ->setPrix(1.20)
            ->setImage('image.jpeg')
            ->setMaraicher($maraicher)
            ->setQuantite(8.5);

        $this->assertTrue($legume->getCategorie() === 'légumes');
        $this->assertTrue($legume->getVariete() === 'tomate');
        $this->assertTrue($legume->getPrix() === 1.20);
        $this->assertTrue($legume->getImage() === 'image.jpeg');
        $this->assertTrue($legume->getMaraicher() === $maraicher);
        $this->assertTrue($legume->getQuantite() === 8.5);
       
    }


    public function testIsFalse(): void
    {
        $legume = new Legume();
        $maraicher = new Maraicher();

        $legume->setCategorie('légumes')
            ->setVariete('tomate')
            ->setPrix(1.20)
            ->setImage('image.jpeg')
            ->setMaraicher($maraicher)
            ->setQuantite(8.5);
           

            $this->assertFalse($legume->getCategorie() === 'fruits');
            $this->assertFalse($legume->getVariete() === 'banane');
            $this->assertFalse($legume->getPrix() === 3.60);
            $this->assertFalse($legume->getImage() === 'false.jpeg');
            $this->assertFalse($legume->getMaraicher() === new Maraicher());
            $this->assertFalse($legume->getQuantite() === 3.9);
           
    }

    public function testIsEmpty()
    {
        $legume = new Legume();

        $this->assertEmpty($legume->getCategorie());
        $this->assertEmpty($legume->getVariete());
        $this->assertEmpty($legume->getPrix());
        $this->assertEmpty($legume->getImage());
        $this->assertEmpty($legume->getMaraicher());
        $this->assertEmpty($legume->getQuantite());
        $this->assertEmpty($legume->getId());
       
    }
}
