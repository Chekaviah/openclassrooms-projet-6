<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CommentControllerTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class CommentControllerTest extends WebTestCase
{
    public function testCommentAdd()
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request(Request::METHOD_GET, '/trick/view/backside-triple-cork-1440');

        $form = $crawler->selectButton('Laisser un commentaire')->form();
        $form['comment[content]'] = 'Test de commentaire';
        $client->submit($form);

        $crawler = $client->followRedirect();

        static::assertSame(1, $crawler->filter('html:contains("Test de commentaire")')->count());
    }
}