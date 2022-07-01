<?php

namespace App\Tests;

use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Entity\Legume;
use PHPUnit\Framework\TestCase;

class DetailsCommandeUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $detailscommande = new DetailsCommande();
        $commande = new Commande();
        $legume = new Legume();
       

        $detailscommande->setQuantite(3.10)
            ->setPrix(1.10)
            ->setCommande($commande)
            ->setLegume($legume);

        $this->assertTrue($detailscommande->getQuantite() === 3.10);
        $this->assertTrue($detailscommande->getPrix() === 1.10);
        $this->assertTrue($detailscommande->getCommande() === $commande);
        $this->assertTrue($detailscommande->getLegume() === $legume);
       
    }


    public function testIsFalse(): void
    {
        $detailscommande = new DetailsCommande();
        $commande = new Commande();
        $legume = new Legume();
       

        $detailscommande->setQuantite(3.10)
            ->setPrix(1.10)
            ->setCommande($commande)
            ->setLegume($legume);

        $this->assertFalse($detailscommande->getQuantite() === 9.99);
        $this->assertFalse($detailscommande->getPrix() === 9);
        $this->assertFalse($detailscommande->getCommande() === new Commande());
        $this->assertFalse($detailscommande->getLegume() === new Legume());
           
    }

    public function testIsEmpty()
    {
        $detailscommande = new DetailsCommande();

        $this->assertEmpty($detailscommande->getQuantite());
        $this->assertEmpty($detailscommande->getPrix());
        $this->assertEmpty($detailscommande->getCommande());
        $this->assertEmpty($detailscommande->getId());
        $this->assertEmpty($detailscommande->getLegume());
    }

    
}
