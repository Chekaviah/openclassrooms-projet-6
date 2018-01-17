<?php

namespace App\Tests\Entity;


use App\Entity\Category;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
	public function testAttributes()
	{
		$trickStub = $this->createMock(Trick::class);
		$trickStub->method('getId')
				  ->willReturn(0);

		$category = new Category();
		$category->setName('Category');
		$category->setSlug('category');
		$category->addTrick($trickStub);

		static::assertNull($category->getId());
		static::assertEquals('Category', $category->getName());
		static::assertEquals('category', $category->getSlug());
		static::assertEquals(0, $category->getTricks()->offsetGet(0)->getId());

		$category->removeTrick($trickStub);

		static::assertEmpty($category->getTricks());
	}
}