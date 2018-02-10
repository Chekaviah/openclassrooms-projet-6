<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserResetPasswordEvent
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class UserResetPasswordEvent extends Event
{
	const NAME = 'user.lost-password';

	/**
	 * @var User
	 */
	private $user;

    /**
     * UserResetPasswordEvent constructor.
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