<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
 // Nous allons tester le formulaire avec des données invalides
class ContactControllerTest extends WebTestCase
{

    private $testData; // On crée une variable privée

    protected function setUp(): void // On crée une fonction setUp qui va être exécutée avant chaque test
    {
        parent::setUp(); // On récupère la fonction setUp de la classe parente
        $jsonData = file_get_contents(__DIR__ . '/DonneesBrut/donneeBrut.json'); // On récupère le contenu du fichier JSON
        $this->testData = json_decode($jsonData, true); // On décode le fichier JSON
    }

    public function testStatus200() // On teste le code 200 
    {
        $client = static::createClient(); // On crée un client
        $attendu = $client->request('GET', '/contact'); // On récupère la page contact
        $this->assertEquals(
            $this->testData['status200']['expectedStatusCode'], // On récupère le code d'erreur attendu
            $client->getResponse()->getStatusCode(),
            "Erreur - Le code d'erreur doit être 200" // On vérifie que le code d'erreur est bien 200
        );
    }

    public function testTagForm() // On teste le tag form
    {
        $client = WebTestCase::createClient(); // On crée un client
        $attendu = $client->request('GET', '/contact'); // On récupère la page contact via la méthode GET
        $this->assertCount(1, $attendu->filter('form'), "Erreur - Doit disposer d'un tag form"); // On vérifie qu'il y a bien un tag form
    }

    public function testFormInputName() // On teste le tag input avec l'attribut name
    {
        $client = WebTestCase::createClient(); // On crée un client pour le test
        $attendu = $client->request('GET', '/contact'); // On récupère la page contact via la méthode GET
        $this->assertCount(1, $attendu->filter('input[name="contact[name]"]'), "Erreur - Doit disposer d'un tag input avec un attribut name avec pour contenu name");
        // On vérifie qu'il y a bien un tag input avec l'attribut name et le contenu name sinon on affiche un message d'erreur
    }


    public function testFormInputEmail() // On teste le tag input avec l'attribut email
    {
        $client = WebTestCase::createClient(); // On crée un client pour le test
        $attendu = $client->request('GET', '/contact'); // On récupère la page contact via la méthode GET
        $this->assertCount(1, $attendu->filter('input[name="contact[email]"]'), "Erreur - Doit disposer d'un tag input avec un attribut name avec pour contenu email");
        // On vérifie qu'il y a bien un tag input avec l'attribut name et le contenu email sinon on affiche un message d'erreur
    }

    //
    public function testFormInputPassword() // On teste le tag input avec l'attribut password
    {
        $client = WebTestCase::createClient(); // On crée un client pour le test
        $attendu = $client->request('GET', '/contact'); // On récupère la page contact via la méthode GET
        $this->assertCount(1, $attendu->filter('input[name="contact[password]"]'), "Erreur - Doit disposer d'un tag input avec un attribut name avec pour contenu password");
        // On vérifie qu'il y a bien un tag input avec l'attribut name et le contenu password sinon on affiche un message d'erreur
    }
    // Nous allons tester le formulaire avec des données 
    public function testFormButtonSubmit() // On teste le bouton submit
    {
        $client = WebTestCase::createClient(); // On crée un client pour le test
        $attendu = $client->request('GET', '/contact'); // On récupère la page contact via la méthode GET
        $this->assertCount(1, $attendu->filter('button[type="submit"]'), "Erreur - Doit disposer d'un button de type submit");
        // On vérifie qu'il y a bien un button de type submit sinon on affiche un message d'erreur
    }

    // Si la page n'existe pas on affiche un message d'erreur

    /**
     * REG-GED-CON-001 : Page not found
     * 
     * Le test doit retourner le status 404 si la page n'existe pas
     *
     * @return void  
     */
    public function testPageNotFound()
    {
        $client = WebTestCase::createClient(); // On crée un client pour le test
        $attendu = $client->request('GET', '/'); // On entre une url qui n'existe pas
         
        $this->assertEquals(
            $this->testData['testStatus404']['expectedStatusCode'], // On récupère le code d'erreur attendu
            $client->getResponse()->getStatusCode(), 
            "Erreur - La page doit retourner le status 404"
        ); // on génère une erreur 404
        // On vérifie que la page n'existe pas sinon on affiche un message d'erreur
    }

    public function testSeConnecter() // Je simule une connexion
    {
        $client = static::createClient(); // je crée un client
        $client->request('GET', '/contact', ['contact[name]' => 'julio', 'contact[password]' => '123456']); // je récupère la page contact avec les données de connexion
        
        $client->submitForm('Validate', ['contact[name]' => 'julio', 'contact[password]' => '123456']); // je soumets le formulaire avec les données de connexion

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); // je vérifie que le code est bien 302

    }
}
