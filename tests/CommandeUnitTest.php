<?php

namespace App\Tests;

use DateTime;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\DetailsCommande;
use PHPUnit\Framework\TestCase;

class CommandeUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $commande = new Commande();
        $date = new DateTime();
        $user = new User();

        $commande->setMontant(8.35)
            ->setStatus(2)
            ->setDate($date)
            ->setUser($user);

        $this->assertTrue($commande->getMontant() === 8.35);
        $this->assertTrue($commande->getStatus() === 2);
        $this->assertTrue($commande->getDate() === $date);
        $this->assertTrue($commande->getUser() === $user);
       
    }


    public function testIsFalse(): void
    {
        $commande = new Commande();
        $date = new DateTime();
        $user = new User();

        $commande->setMontant(8.35)
            ->setStatus(2)
            ->setDate($date)
            ->setUser($user);

        $this->assertFalse($commande->getMontant() === 9.99);
        $this->assertFalse($commande->getStatus() === 0);
        $this->assertFalse($commande->getDate() === new DateTime());
        $this->assertFalse($commande->getUser() === new User());
           
    }

    public function testIsEmpty()
    {
        $commande = new Commande();

        $this->assertEmpty($commande->getMontant());
        $this->assertEmpty($commande->getStatus());
        $this->assertEmpty($commande->getDate());
        $this->assertEmpty($commande->getId());
    }

    public function testAddGetRemoveDetailsCommande()
    {
        $commande = new Commande();
        $detailsCommande = new DetailsCommande();

        //Vérifie si il n'y a aucun details commande associé à la commande
        $this->assertEmpty($commande->getDetailsCommandes());

        //Ajoute un détail commande à la commande
        //Vérifie qu'on récupére bien le détail commande
        $commande->addDetailsCommande($detailsCommande);
        $this->assertContains($detailsCommande, $commande->getDetailsCommandes());

        //Remove le détail commande
        //Vérifie que le détail commande de la commande est vide
        $commande->removeDetailsCommande($detailsCommande);
        $this->assertEmpty($commande->getDetailsCommandes());
    }
}
