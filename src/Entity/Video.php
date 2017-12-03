<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trick;

/**
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
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
	 * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="videos")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $trick;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $url
	 * @return Video
	 */
	public function setUrl($url): Video
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
	 * @param Trick $trick
	 * @return Video
	 */
	public function setTrick(Trick $trick): Video
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
}
