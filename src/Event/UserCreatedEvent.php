<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserCreatedEvent
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class UserCreatedEvent extends Event
{
	const NAME = 'user.created';

	/**
	 * @var User
	 */
	private $user;

    /**
     * UserCreatedEvent constructor.
     *
     * @param User $user
     */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

    /**
     * @param $user
     */
	public function setUser($user)
	{
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}
}
