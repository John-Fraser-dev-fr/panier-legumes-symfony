<?php

namespace App\Tests;

use App\Entity\Commande;
use DateTime;
use PHPUnit\Framework\TestCase;

class CommandeUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $commande = new Commande();
        $date = new DateTime();

        $commande->setMontant(8.35)
            ->setStatus(2)
            ->setDate($date);

        $this->assertTrue($commande->getMontant() === 8.35);
        $this->assertTrue($commande->getStatus() === 2);
        $this->assertTrue($commande->getDate() === $date);
       
    }


    public function testIsFalse(): void
    {
        $commande = new Commande();
        $date = new DateTime();

        $commande->setMontant(8.35)
            ->setStatus(2)
            ->setDate($date);

        $this->assertFalse($commande->getMontant() === 9.99);
        $this->assertFalse($commande->getStatus() === 0);
        $this->assertFalse($commande->getDate() === new DateTime());
           
    }

    public function testIsEmpty()
    {
        $commande = new Commande();

        $this->assertEmpty($commande->getMontant());
        $this->assertEmpty($commande->getStatus());
        $this->assertEmpty($commande->getDate());
      
       
    }
}
