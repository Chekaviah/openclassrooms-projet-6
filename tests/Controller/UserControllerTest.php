<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    public function testProfileEdit()
    {
        $client = $this->getClient('admin');
        $crawler = $client->request(Request::METHOD_GET, '/profile');

        $form = $crawler->selectButton('Enregistrer')->form();
        $form['profile[username]'] = 'admintest';
        $client->submit($form);

        $client = $this->getClient('admintest');
        $crawler = $client->request(Request::METHOD_GET, '/profile');

        $name = $crawler->filter('a.nav-link u')->first()->text();
        static::assertSame('admintest', $name);
    }

    public function getClient(string $name)
    {
        return self::createClient(array(), array(
            'PHP_AUTH_USER' => $name,
            'PHP_AUTH_PW'   => 'admin',
        ));
    }
}