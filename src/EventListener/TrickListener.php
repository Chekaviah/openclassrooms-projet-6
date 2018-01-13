<?php

namespace App\EventListener;

use App\Entity\Trick;
use DateTime;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class TrickListener
{
	/**
	 * @param Trick $trick
	 * @param LifecycleEventArgs $event
	 * @ORM\PrePersist
	 */
	public function prePersistHandler(Trick $trick)
	{
		$now = new DateTime('NOW');
		$trick->setCreated($now);
		$trick->setUpdated($now);
	}

	/**
	 * @param Trick $trick
	 * @param LifecycleEventArgs $event
	 * @ORM\PreUpdate
	 */
	public function preUpdateHandler(Trick $trick)
	{
		$now = new DateTime('NOW');
		$trick->setUpdated($now);
	}
}
