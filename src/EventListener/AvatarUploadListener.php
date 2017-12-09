<?php

namespace App\EventListener;

use App\Entity\Avatar;
use App\Service\Uploader;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class AvatarUploadListener
{
	/**
	 * @var Uploader
	 */
	private $uploader;

	/**
	 * @var string
	 */
	private $directory;

	/**
	 * AvatarUploadListener constructor.
	 * @param Uploader $uploader
	 * @param $directory
	 */
	public function __construct(Uploader $uploader, $directory)
	{
		$this->uploader = $uploader;
		$this->directory = $directory;
	}

	/**
	 * @param Avatar $avatar
	 * @param LifecycleEventArgs $event
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function prePersistHandler(Avatar $avatar, LifecycleEventArgs $event)
	{
		if(!$avatar instanceof Avatar)
			return;

		if($avatar->getFile() === null)
			return;

		$extension = $this->uploader->getExtension($avatar->getFile());

		$avatar->setName(md5(uniqid()));
		$avatar->setExtension($extension);
	}

	/**
	 * @param Avatar $avatar
	 * @param LifecycleEventArgs $event
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function postPersistHandler(Avatar $avatar, LifecycleEventArgs $event)
	{
		if(!$avatar instanceof Avatar)
			return;

		if ($avatar->getTempFilename() !== null) {
			$oldFile = $this->directory.'/'.$avatar->getTempFilename();
			if (file_exists($oldFile))
				unlink($oldFile);
		}

		$this->uploader->setTargetDir($this->directory);
		$this->uploader->upload($avatar->getFile(), $avatar->getName());
	}

	/**
	 * @param Avatar $avatar
	 * @param LifecycleEventArgs $event
	 * @ORM\PreRemove()
	 */
	public function preRemoveHandler(Avatar $avatar, LifecycleEventArgs $event)
	{
		if(!$avatar instanceof Avatar)
			return;

		$avatar->setTempFilename();
	}

	/**
	 * @param Avatar $avatar
	 * @param LifecycleEventArgs $event
	 * @ORM\PostRemove()
	 */
	public function postRemoveHandler(Avatar $avatar, LifecycleEventArgs $event)
	{
		if(!$avatar instanceof Avatar)
			return;

		if (file_exists($this->directory.'/'.$avatar->getTempFilename()))
			unlink($this->directory.'/'.$avatar->getTempFilename());
	}
}