<?php

namespace Alt87\Bundle\TodoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/task/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /task/");
        $crawler = $client->click($crawler->selectLink('Créer une nouvelle tâche')->link());
        
        // Fill in the form and submit it
        $form = $crawler->selectButton('Créer')->form(array(
            'alt87_bundle_todobundle_task[name]'  => 'Test',
            
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Éditer')->link());

        $form = $crawler->selectButton('Modifier')->form(array(
            'alt87_bundle_todobundle_task[name]'  => 'Foo',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Supprimer')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
    
    public function testEmptyTask()
    {
        // Création du client de test.
        $client = static::createClient();

        // Chargement de la vue de création d'une tache.
        $crawler = $client->request('GET', '/task/new');
        
        // Envoi du formulaire sans nom pour la tâche.
        $form = $crawler->selectButton('Créer')->form(array(
            'alt87_bundle_todobundle_task[name]' => '',
        ));
        
        $crawler = $client->submit($form);
        // Vérifie la présence du message d'erreur.
        $this->assertTrue($crawler->filter('html:contains("Ce champ est obligatoire.")')->count() > 0);
    }

}
