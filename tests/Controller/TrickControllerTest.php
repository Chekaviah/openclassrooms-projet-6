<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TrickControllerTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class TrickControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/');

        static::assertSame(1, $crawler->filter('.cover-catch')->count());
    }

    public function testTrickView()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/trick/view/bs-540-seatbelt');

        static::assertSame(1, $crawler->filter('html:contains("Hitsch aurait tout aussi bien pu faire de la danse classique mais il s’est décidé pour la neige.")')->count());
    }

    public function testTrickCreate()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request(Request::METHOD_GET, '/trick/create');

        $form = $crawler->selectButton('Enregistrer')->form();
        $form['trick[name]'] = 'Test de trick';
        $form['trick[slug]'] = 'test-de-trick';
        $form['trick[description]'] = 'Description du trick';
        $form['trick[categories]']->select(array(3, 4));
        $client->submit($form);

        $crawler = $client->followRedirect();

        static::assertSame(1, $crawler->filter('html:contains("Test de trick")')->count());
    }

    public function testTrickEdit()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request(Request::METHOD_GET, '/trick/edit/1');

        $form = $crawler->selectButton('Enregistrer')->form();
        $form['trick[name]'] = 'Titre du trick';
        $form['trick[slug]'] = 'slug-du-trick';
        $form['trick[description]'] = 'Description du trick';
        $form['trick[categories]']->select(array(1, 2));
        $client->submit($form);

        $crawler = $client->followRedirect();

        static::assertSame(1, $crawler->filter('html:contains("Titre du trick")')->count());
        static::assertSame(1, $crawler->filter('html:contains("Description du trick")')->count());
        static::assertSame(1, $crawler->filter('html:contains("Grabs")')->count());
        static::assertSame(1, $crawler->filter('html:contains("Rotations")')->count());
    }

    public function testTrickDelete()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request(Request::METHOD_GET, '/trick/view/test-de-trick');

        $form = $crawler->selectButton('trick-delete-button')->form();
        $client->submit($form);

        $crawler = $client->followRedirect();

        static::assertSame(1, $crawler->filter('html:contains("Le trick a bien été supprimé.")')->count());
    }

}