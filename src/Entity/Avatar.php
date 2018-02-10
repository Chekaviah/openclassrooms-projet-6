<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AvatarClientPostAction
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="App\Repository\AvatarRepository")
 * @ORM\EntityListeners({"App\EventListener\AvatarUploadListener"})
 */
class Avatar
{
    /**
	 * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var string
     *
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;

	/**
	 * @var string
     *
	 * @ORM\Column(name="extension", type="string", length=255)
	 */
	private $extension;

	/**
	 * @var UploadedFile
	 */
	private $file;

	/**
	 * @var string
	 */
	private $tempFilename;

	/**
	 * @return int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $extension
	 */
	public function setExtension($extension)
	{
		$this->extension = $extension;
	}

	/**
	 * @return string
	 */
	public function getExtension(): ?string
	{
		return $this->extension;
	}

	/**
	 * @return UploadedFile
	 */
	public function getFile(): ?UploadedFile
	{
		return $this->file;
	}

	/**
	 * @param UploadedFile|null $file
	 */
	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;

		if ($this->extension !== null) {
			$this->setTempFilename();

			$this->url = null;
			$this->alt = null;
		}
	}

	/**
	 */
	public function setTempFilename()
	{
		$this->tempFilename = $this->name.'.'.$this->extension;
	}

	/**
	 * @return string
	 */
	public function getTempFilename(): ?string
	{
		return $this->tempFilename;
	}

	/**
	 * @return string
	 */
	public function getUploadDir(): string
	{
		return 'uploads/avatars';
	}

	/**
	 * @return string
	 */
	public function getUploadRootDir(): string
	{
		return __DIR__.'/../../public/'.$this->getUploadDir();
	}

	/**
	 * @return string
	 */
	public function getPath(): ?string
	{
		return $this->getUploadDir().'/'.$this->getName().'.'.$this->getExtension();
	}
}
