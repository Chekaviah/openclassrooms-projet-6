<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Avatar;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class UserTest extends TestCase
{
	public function testAttributes()
	{
		$avatarStub = $this->createMock(Avatar::class);
		$avatarStub->method('getId')
			->willReturn(0);

		$user = new User();
		$user->setUsername('username');
		$user->setPlainPassword('plainpassword');
		$user->setPassword('password');
		$user->setEmail('user@website.net');
		$user->setActive(true);
		$user->setRoles(['ROLE_USER']);
		$user->setConfirmationToken('confirmationtoken');
		$user->setResetToken('resettoken');
		$user->setAvatar($avatarStub);

		static::assertNull($user->getId());
		static::assertTrue($user->getActive());
		static::assertEquals('username', $user->getUsername());
		static::assertEquals('plainpassword', $user->getPlainPassword());
		static::assertEquals('password', $user->getPassword());
		static::assertEquals('user@website.net', $user->getEmail());
		static::assertEquals(['ROLE_USER'], $user->getRoles());
		static::assertEquals('confirmationtoken', $user->getConfirmationToken());
		static::assertEquals('resettoken', $user->getResetToken());
		static::assertEquals(0, $user->getAvatar()->getId());

		static::assertNull($user->getAvatarPath());

		$user->setAvatar(null);
		static::assertEquals('/img/avatar.jpg', $user->getAvatarPath());
	}

	public function testSerialization()
	{
		$user = new User();
		$user->setUsername('username');
		$user->setPassword('password');
		$user->setActive(true);

		$serial = $user->serialize();

		$user->setUsername(null);
		$user->setPassword(null);
		$user->setActive(false);

		$user->unserialize($serial);

		static::assertNull($user->getId());
		static::assertTrue($user->getActive());
		static::assertEquals('username', $user->getUsername());
		static::assertEquals('password', $user->getPassword());
	}
}