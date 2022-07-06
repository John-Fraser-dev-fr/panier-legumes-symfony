<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationFunctionnalTest extends WebTestCase
{

    public function testDisplayRegistrationPageUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Inscription');
    }


    public function testWhenUserRegisters(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/inscription');

        //Cherche le bouton connexion
        $buttonCrawlerNode = $crawler->selectButton('Inscription');
        //Récupére le formulaire associé au bouton
        $formUser = $buttonCrawlerNode->form();

        //Passe des paramétres aux champs du formulaire
        $formUser = $buttonCrawlerNode->form([
            'user[prenom]' => 'john',
            'user[nom]' => 'fraser',
            'user[email]' => 'test@test.fr',
            'user[password]' => 'password',
            'user[phone]' => '0123456789',
            'user[n_rue]' => '12',
            'user[rue]' => 'rue du test',
            'user[cd_postal]' => '75015',
            'user[ville]' => 'Paris'
        ]);
        //Soumet le formulaire
        $client->submit($formUser);

        $crawler = $client->request('GET', '/user/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Connexion');
    }


    public function testDisplayRegistrationPageMaraicher(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/maraicher/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Inscription');
    }


    public function testWhenMaraicherRegisters(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/maraicher/inscription');

        //Cherche le bouton connexion
        $buttonCrawlerNode = $crawler->selectButton('Inscription');
        //Récupére le formulaire associé au bouton
        $formMaraicher = $buttonCrawlerNode->form();

        //Passe des paramétres aux champs du formulaire
        $formMaraicher = $buttonCrawlerNode->form([
            'maraicher[prenom]' => 'john',
            'maraicher[nom]' => 'fraser',
            'maraicher[email]' => 'test@test.fr',
            'maraicher[password]' => 'password',
            'maraicher[ville]' => 'Paris',
            'maraicher[entreprise]' => 'Entreprise',
            'maraicher[logo]' => 'logo.jpg',
            
        ]);
        //Soumet le formulaire
        $client->submit($formMaraicher);

        $crawler = $client->request('GET', '/maraicher/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Connexion');
    }

}
