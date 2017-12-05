<?php

namespace App\Tests\Service;

use App\Service\UrlExtractor;
use PHPUnit\Framework\TestCase;

class UrlExtractorTest extends TestCase
{
	/**
	 * @param string $url
	 * @param string $id
	 * @dataProvider getLinks
	 */
	public function testExtractId($url, $id)
	{
		$this->assertSame($id, UrlExtractor::extractId($url)['id']);
	}

	/**
	 * @return \Generator
	 */
	public function getLinks()
	{
		yield ['http://www.dailymotion.com/embed/video/videoid', 'videoid'];
		yield ['http://www.dailymotion.com/video/videoid', 'videoid'];
		yield ['http://www.dailymotion.com/swf/video/videoid', 'videoid'];
		yield ['youtube.com/v/ytubvideoid', 'ytubvideoid'];
		yield ['youtube.com/?v=ytubvideoid', 'ytubvideoid'];
		yield ['youtube.com/watch?v=ytubvideoid', 'ytubvideoid'];
		yield ['youtu.be/ytubvideoid', 'ytubvideoid'];
		yield ['youtube.com/embed/ytubvideoid', 'ytubvideoid'];
		yield ['http://youtube.com/v/ytubvideoid', 'ytubvideoid'];
		yield ['http://www.youtube.com/v/ytubvideoid', 'ytubvideoid'];
		yield ['https://www.youtube.com/v/ytubvideoid', 'ytubvideoid'];
		yield ['youtube.com/watch?v=ytubvideoid&wtv=wtv', 'ytubvideoid'];
		yield ['http://www.youtube.com/watch?dev=inprogress&v=ytubvideoid&feature=related', 'ytubvideoid'];
	}
}