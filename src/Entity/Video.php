<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trick;
use Symfony\Component\Validator\Constraints as Assert;

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
	 * @var string
	 * @ORM\Column(name="website", type="string", length=255)
	 * @Assert\Choice({"youtube", "dailymotion"})
	 */
	private $website;

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
	public function setUrl(string $url): Video
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
	 * @param string $website
	 */
	public function setWebsite(string $website)
	{
		$this->website = $website;
	}

	/**
	 * @return string
	 */
	public function getWebsite(): ?string
	{
		return $this->website;
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

	/**
	 * @return string
	 */
	public function getIframe(): ?string
	{
		$url = '';
		if($this->website == 'youtube') {
			$url = 'https://www.youtube.com/embed/'.$this->url;
		} else {
			$url = 'https://www.dailymotion.com/embed/video/'.$this->url;
		}

		return $url;
	}
}
