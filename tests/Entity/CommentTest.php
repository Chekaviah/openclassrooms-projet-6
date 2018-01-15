<?php

namespace App\Tests\Entity;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
	public function testAttributes()
	{
		$now = new \DateTime('NOW');

		$trickStub = $this->createMock(Trick::class);
		$trickStub->method('getId')
			->willReturn(0);

		$userStub = $this->createMock(User::class);
		$userStub->method('getId')
			->willReturn(0);

		$comment = new Comment();
		$comment->setDate($now);
		$comment->setContent('comment');
		$comment->setUser($userStub);
		$comment->setTrick($trickStub);

		static::assertNull($comment->getId());
		static::assertEquals($now, $comment->getDate());
		static::assertEquals('comment', $comment->getContent());
		static::assertEquals(0, $comment->getUser()->getId());
		static::assertEquals(0, $comment->getTrick()->getId());
	}
}