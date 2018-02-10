<?php

namespace App\Entity;

use App\Entity\Trick;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Image
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\EntityListeners({"App\EventListener\ImageUploadListener"})
 */
class Image
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
	 * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="images")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $trick;

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
	public function setName(string $name)
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
	public function setExtension(string $extension)
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
	 * @param Trick $trick
	 */
	public function setTrick(Trick $trick)
	{
		$this->trick = $trick;
	}

	/**
	 * @return Trick
	 */
	public function getTrick(): ?Trick
	{
		return $this->trick;
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
		return 'uploads/images';
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
