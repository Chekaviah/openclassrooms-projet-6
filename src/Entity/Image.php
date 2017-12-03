<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trick;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
	 * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var string
	 * @ORM\Column(name="url", type="string", length=255)
	 */
	private $url;

	/**
	 * @var string
	 * @ORM\Column(name="alt", type="string", length=255)
	 */
	private $alt;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="images")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $trick;

	/**
	 * @var UploadedFile
	 */
	private $file;

	private $tempFilename;



	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload()
	{
		if ($this->file === null)
			return;

		$this->url = $this->file->guessExtension();
		$this->alt = $this->file->getClientOriginalName();
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload()
	{
		if ($this->file === null)
			return;

		if ($this->tempFilename !== null) {
			$oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
			if (file_exists($oldFile))
				unlink($oldFile);
		}

		$name = $this->file->getClientOriginalName();
		$this->file->move(
			$this->getUploadRootDir(),
			$this->id.'.'.$this->url
		);

		$this->url = $name;
		$this->alt = $name;
	}

	/**
	 * @ORM\PreRemove()
	 */
	public function preRemoveUpload()
	{
		$this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload()
	{
		if (file_exists($this->tempFilename))
			unlink($this->tempFilename);
	}

	public function getUploadDir()
	{
		return 'uploads/img';
	}

	public function getUploadRootDir()
	{
		return __DIR__.'/../../public/'.$this->getUploadDir();
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param string $url
	 * @return Image
	 */
	public function setUrl($url): Image
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}

	/**
	 * @param string $alt
	 * @return Image
	 */
	public function setAlt($alt): Image
	{
		$this->alt = $alt;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAlt(): ?string
	{
		return $this->alt;
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

		if ($this->url !== null) {
			$this->tempFilename = $this->url;

			$this->url = null;
			$this->alt = null;
		}
	}

	/**
	 * @param Trick $trick
	 * @return Image
	 */
	public function setTrick(Trick $trick): Image
	{
		$this->trick = $trick;

		return $this;
	}

	/**
	 * @return Trick
	 */
	public function getTrick(): ?Trick
	{
		return $this->trick;
	}

	/**
	 * @return string
	 */
	public function getPath(): ?string
	{
		return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
	}
}
