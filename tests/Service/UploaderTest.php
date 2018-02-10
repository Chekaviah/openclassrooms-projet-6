<?php

namespace App\Tests\Service;

use App\Service\Uploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploaderTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class UploaderTest extends TestCase
{
	public function testTargetDir()
	{
		$uploader = new Uploader('');

		static::assertEquals('', $uploader->getTargetDir());

		$uploader->setTargetDir('directory');
		static::assertEquals('directory', $uploader->getTargetDir());
	}

	public function testFileAttributes()
	{
		$uploader = new Uploader('');

		$fileStub = $this->createMock(UploadedFile::class);
		$fileStub->method('guessExtension')
			->willReturn('extension');
		$fileStub->method('getClientOriginalName')
			->willReturn('name');

		static::assertEquals('extension', $uploader->getExtension($fileStub));
		static::assertEquals('name', $uploader->getOriginalName($fileStub));
	}
}