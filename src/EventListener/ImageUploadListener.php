<?php

namespace App\EventListener;

use App\Entity\Image;
use App\Service\Uploader;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class ImageUploadListener
{
	private $uploader;

	public function __construct(Uploader $uploader, $directory)
	{
		$this->uploader = $uploader;
		$this->uploader->setTargetDir($directory);
	}

	/**
	 * @param Image $image
	 * @param LifecycleEventArgs $event
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function prePersistHandler(Image $image, LifecycleEventArgs $event)
	{
		if($image->getFile() === null)
			return;

		$extension = $this->uploader->getExtension($image->getFile());

		$image->setName(md5(uniqid()));
		$image->setExtension($extension);
	}

	/**
	 * @param Image $image
	 * @param LifecycleEventArgs $event
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function postPersistHandler(Image $image, LifecycleEventArgs $event)
	{
		if ($image->getTempFilename() !== null) {
			$oldFile = $this->uploader->getTargetDir().'/'.$image->getTempFilename();
			if (file_exists($oldFile))
				unlink($oldFile);
		}

		$this->uploader->upload($image->getFile(), $image->getName());
	}

	/**
	 * @param Image $image
	 * @param LifecycleEventArgs $event
	 * @ORM\PreRemove()
	 */
	public function preRemoveHandler(Image $image, LifecycleEventArgs $event)
	{
		$image->setTempFilename();
	}

	/**
	 * @param Image $image
	 * @param LifecycleEventArgs $event
	 * @ORM\PostRemove()
	 */
	public function postRemoveHandler(Image $image, LifecycleEventArgs $event)
	{
		if (file_exists($this->uploader->getTargetDir().'/'.$image->getTempFilename()))
			unlink($this->uploader->getTargetDir().'/'.$image->getTempFilename());
	}
}