<?php

namespace App\Tests\Entity;


use App\Entity\Avatar;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarTest extends TestCase
{
	public function testAttributes()
	{
		$fileStub = $this->createMock(UploadedFile::class);

		$avatar = new Avatar();
		$avatar->setName('image');
		$avatar->setExtension('jpg');
		$avatar->setFile($fileStub);

		static::assertNull($avatar->getId());
		static::assertEquals('image', $avatar->getName());
		static::assertEquals('jpg', $avatar->getExtension());
		static::assertInstanceOf(UploadedFile::class, $avatar->getFile());
		static::assertEquals('image.jpg', $avatar->getTempFilename());
		static::assertEquals('uploads/avatars/image.jpg', $avatar->getPath());
	}
}