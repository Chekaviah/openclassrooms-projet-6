<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class ImageTest extends TestCase
{
	public function testAttributes()
	{
		$trickStub = $this->createMock(Trick::class);
		$trickStub->method('getId')
			->willReturn(0);

		$fileStub = $this->createMock(UploadedFile::class);

		$image = new Image();
		$image->setName('image');
		$image->setExtension('jpg');
		$image->setFile($fileStub);
		$image->setTrick($trickStub);

		static::assertNull($image->getId());
		static::assertEquals('image', $image->getName());
		static::assertEquals('jpg', $image->getExtension());
		static::assertInstanceOf(UploadedFile::class, $image->getFile());
		static::assertEquals('image.jpg', $image->getTempFilename());
		static::assertEquals('uploads/images/image.jpg', $image->getPath());
		static::assertEquals(0, $image->getTrick()->getId());
	}
}