<?php

namespace App\EventListener;

use App\Entity\Image;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Class ImageUploadListener
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class ImageUploadListener
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
	 * ImageUploadListener constructor.
     *
	 * @param Uploader $uploader
	 * @param $directory
	 */
	public function __construct(Uploader $uploader, $directory)
	{
		$this->uploader = $uploader;
		$this->directory = $directory;
	}

	/**
	 * @param Image $image
	 *
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function prePersistHandler(Image $image)
	{
		$extension = $this->uploader->getExtension($image->getFile());

		$image->setName(md5(uniqid()));
		$image->setExtension($extension);
	}

	/**
	 * @param Image $image
	 *
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function postPersistHandler(Image $image)
	{
		if ($image->getTempFilename() !== null) {
			$oldFile = $this->directory.'/'.$image->getTempFilename();
			if (file_exists($oldFile))
				unlink($oldFile);
		}

		$this->uploader->setTargetDir($this->directory);
		$this->uploader->upload($image->getFile(), $image->getName());
	}

	/**
	 * @param Image $image
	 *
	 * @ORM\PreRemove()
	 */
	public function preRemoveHandler(Image $image)
	{
		$image->setTempFilename();
	}

	/**
	 * @param Image $image
	 *
	 * @ORM\PostRemove()
	 */
	public function postRemoveHandler(Image $image)
	{
		if (file_exists($this->directory.'/'.$image->getTempFilename()))
			unlink($this->directory.'/'.$image->getTempFilename());
	}
}
