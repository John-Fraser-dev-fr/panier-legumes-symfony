<?php

namespace App\Tests;

use App\Entity\Legume;
use PHPUnit\Framework\TestCase;

class LegumeUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $legume = new Legume();

        $legume->setCategorie('légumes')
            ->setVariete('tomate')
            ->setPrix(1.20)
            ->setImage('image.jpeg');

        $this->assertTrue($legume->getCategorie() === 'légumes');
        $this->assertTrue($legume->getVariete() === 'tomate');
        $this->assertTrue($legume->getPrix() === 1.20);
        $this->assertTrue($legume->getImage() === 'image.jpeg');
       
    }


    public function testIsFalse(): void
    {
        $legume = new Legume();

        $legume->setCategorie('légumes')
            ->setVariete('tomate')
            ->setPrix(1.20)
            ->setImage('image.jpeg');
           

            $this->assertFalse($legume->getCategorie() === 'fruits');
            $this->assertFalse($legume->getVariete() === 'banane');
            $this->assertFalse($legume->getPrix() === 3.60);
            $this->assertFalse($legume->getImage() === 'false.jpeg');
           
    }

    public function testIsEmpty()
    {
        $legume = new Legume();

        $this->assertEmpty($legume->getCategorie());
        $this->assertEmpty($legume->getVariete());
        $this->assertEmpty($legume->getPrix());
        $this->assertEmpty($legume->getImage());
       
    }
}
