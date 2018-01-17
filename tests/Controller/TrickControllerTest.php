<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
	/**
	 * @param $url
	 * @dataProvider urlProvider
	 */
	public function testPageIsSuccessful($url)
	{
		$client = self::createClient(array(), array(
			'PHP_AUTH_USER' => 'user',
			'PHP_AUTH_PW'   => 'user',
		));
		$client->request('GET', $url);

		$this->assertTrue($client->getResponse()->isSuccessful());
	}

	public function urlProvider()
	{
		yield ['/'];
		yield ['/trick/view/bs-540-seatbelt'];
		yield ['/trick/create'];
	}
}