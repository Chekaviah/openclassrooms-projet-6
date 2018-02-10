<?php

namespace App\Tests\Service;

use App\Service\Slugger;
use PHPUnit\Framework\TestCase;

/**
 * Class SluggerTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class SluggerTest extends TestCase
{
	/**
	 * @param string $string
	 * @param string $slug
	 * @dataProvider getSlugs
	 */
	public function testSlugify($string, $slug)
	{
		$this->assertSame($slug, Slugger::slugify($string));
	}

	/**
	 * @return \Generator
	 */
	public function getSlugs()
	{
		yield ['Lorem Ipsum', 'lorem-ipsum'];
		yield ['  Lorem Ipsum  ', 'lorem-ipsum'];
		yield [' lOrEm  iPsUm  ', 'lorem-ipsum'];
		yield ['!Lorem Ipsum!', 'lorem-ipsum'];
		yield ['lorem-ipsum', 'lorem-ipsum'];
	}
}