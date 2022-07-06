<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFunctionnalTest extends WebTestCase
{
    public function testDisplayLoginPageUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Déjà client ?');
    }

    public function testDisplayLoginPageMaraicher(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/maraicher/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Vous êtes maraîcher ?');
    }

    public function testWhenUserLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/login');

        //Cherche le bouton connexion
        $buttonCrawlerNode = $crawler->selectButton('Connexion');
        //Récupére le formulaire associé au bouton
        $formUser = $buttonCrawlerNode->form();

        //Passe des paramétres aux champs du formulaire
        $formUser = $buttonCrawlerNode->form([
            '_username' => 'test@test.fr',
            '_password' => 'password'
        ]);
        //Soumet le formulaire
        $client->submit($formUser);

        $crawler = $client->request('GET', '/legumes');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Bienvenue !');
    }

    public function testWhenMaraicherLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/maraicher/login');

        //Cherche le bouton connexion
        $buttonCrawlerNode = $crawler->selectButton('Connexion');
        //Récupére le formulaire associé au bouton
        $formUser = $buttonCrawlerNode->form();

        //Passe des paramétres aux champs du formulaire
        $formUser = $buttonCrawlerNode->form([
            '_username' => 'maraicher@test.fr',
            '_password' => 'password'
        ]);
        //Soumet le formulaire
        $client->submit($formUser);

        $crawler = $client->request('GET', '/maraicher/index');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Bienvenue Maraicher !');

    }
}
