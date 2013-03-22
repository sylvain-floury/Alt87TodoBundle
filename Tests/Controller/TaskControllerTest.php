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
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'alt87_bundle_todobundle_tasktype[name]'  => 'Test',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Edit')->form(array(
            'alt87_bundle_todobundle_tasktype[name]'  => 'Foo',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('[value="Foo"]')->count() > 0);

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
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
        $form = $crawler->selectButton('Create')->form(array(
            'alt87_bundle_todobundle_tasktype[name]'  => '',
        ));
        
        $crawler = $client->submit($form);
        // Vérifie la présence du message d'erreur.
        $this->assertTrue($crawler->filter('html:contains("Veuillez saisir le nom de la tache.")')->count() > 0);
    }
}