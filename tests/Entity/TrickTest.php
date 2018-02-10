<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Comment;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

/**
 * Class TrickTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class TrickTest extends TestCase
{
	public function testAttributes()
	{
		$now = new DateTime('NOW');

		$categoryStub = $this->createMock(Category::class);
		$categoryStub->method('getId')
			->willReturn(0);

		$imageStub = $this->createMock(Image::class);
		$imageStub->method('getId')
			->willReturn(0);

		$videoStub = $this->createMock(Video::class);
		$videoStub->method('getId')
			->willReturn(0);

		$commentStub = $this->createMock(Comment::class);
		$commentStub->method('getId')
			->willReturn(0);

		$trick = new Trick();
		$trick->setName('Trick');
		$trick->setSlug('trick');
		$trick->setDescription('Trick description');
		$trick->setCreated($now);
		$trick->setUpdated($now);
		$trick->addCategory($categoryStub);
		$trick->addImage($imageStub);
		$trick->addVideo($videoStub);
		$trick->addComment($commentStub);

		static::assertNull($trick->getId());
		static::assertEquals('Trick', $trick->getName());
		static::assertEquals('trick', $trick->getSlug());
		static::assertEquals('Trick description', $trick->getDescription());
		static::assertEquals($now, $trick->getCreated());
		static::assertEquals($now, $trick->getUpdated());
		static::assertEquals(0, $trick->getCategories()->offsetGet(0)->getId());
		static::assertEquals(0, $trick->getImages()->offsetGet(0)->getId());
		static::assertEquals(0, $trick->getVideos()->offsetGet(0)->getId());
		static::assertEquals(0, $trick->getComments()->offsetGet(0)->getId());

		$trick->removeCategory($categoryStub);
		$trick->removeImage($imageStub);
		$trick->removeVideo($videoStub);
		$trick->removeComment($commentStub);

		static::assertEmpty($trick->getCategories());
		static::assertEmpty($trick->getImages());
		static::assertEmpty($trick->getVideos());
		static::assertEmpty($trick->getComments());
	}
}