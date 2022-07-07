<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        //Cherche le bouton inscription
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

        //Cherche le bouton inscription
        $buttonCrawlerNode = $crawler->selectButton('Inscription');
        //Récupére le formulaire associé au bouton
        $formMaraicher = $buttonCrawlerNode->form();

        //Récupére le fichier téléchargé
        $logo = new UploadedFile(__DIR__. '/test-files/logoTest.jpeg', 'logoTest.jpeg');

        //Passe des paramétres aux champs du formulaire
        $formMaraicher['maraicher[prenom]'] = 'john';
        $formMaraicher['maraicher[nom]'] = 'fraser';
        $formMaraicher['maraicher[email]'] = 'test@test.fr';
        $formMaraicher['maraicher[password]'] = 'password';
        $formMaraicher['maraicher[ville]'] = 'paris';
        $formMaraicher['maraicher[entreprise]'] = 'entreprise';
        $formMaraicher['maraicher[logo]'] = $logo;
      
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
