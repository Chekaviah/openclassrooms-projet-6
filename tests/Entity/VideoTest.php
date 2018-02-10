<?php

namespace App\Tests\Entity;

use App\Entity\Trick;
use App\Entity\Video;
use PHPUnit\Framework\TestCase;

/**
 * Class VideoTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class VideoTest extends TestCase
{
	public function testAttributes()
	{
		$trickStub = $this->createMock(Trick::class);
		$trickStub->method('getId')
			->willReturn(0);

		$video = new Video();
		$video->setUrl('videoid');
		$video->setWebsite('youtube');
		$video->setTrick($trickStub);

		static::assertNull($video->getId());
		static::assertEquals('videoid', $video->getUrl());
		static::assertEquals('youtube', $video->getWebsite());
		static::assertEquals(0, $video->getTrick()->getId());
		static::assertEquals('https://www.youtube.com/embed/videoid', $video->getIframe());

		$video->setWebsite('dailymotion');

		static::assertEquals('https://www.dailymotion.com/embed/video/videoid', $video->getIframe());
	}
}