<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Category;
use App\Entity\Image;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @ORM\EntityListeners({"App\EventListener\TrickListener"})
 */
class Trick
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
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	private $slug;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $updated;

	/**
	 * @var Category[]|ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Category", cascade={"persist"}, inversedBy="tricks")
	 * @JoinTable(name="trick_category")
	 */
	private $categories;

	/**
	 * @var Image[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="App\Entity\Image", cascade={"persist", "remove", "refresh"}, mappedBy="trick", orphanRemoval=true)
	 */
	private $images;

	/**
	 * @var Video[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="App\Entity\Video", cascade={"persist", "remove", "refresh"}, mappedBy="trick", orphanRemoval=true)
	 */
	private $videos;

	/**
	 * @var Comment[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="App\Entity\Comment", cascade={"persist", "remove", "refresh"}, mappedBy="trick", orphanRemoval=true)
	 */
	private $comments;

	public function __construct()
	{
		$this->images = new ArrayCollection();
		$this->videos = new ArrayCollection();
		$this->categories = new ArrayCollection();
		$this->comments = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
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
	public function getSlug(): ?string
	{
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function setSlug(string $slug)
	{
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function getDescription(): ?string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated(): ?DateTime
	{
		return $this->created;
	}

	/**
	 * @param DateTime $created
	 */
	public function setCreated(DateTime $created)
	{
		$this->created = $created;
	}

	/**
	 * @return DateTime
	 */
	public function getUpdated(): ?DateTime
	{
		return $this->updated;
	}

	/**
	 * @param DateTime $updated
	 */
	public function setUpdated(DateTime $updated)
	{
		$this->updated = $updated;
	}

	/**
	 * @param Category $category
	 */
	public function addCategory(Category $category)
	{
		$this->categories[] = $category;
	}

	/**
	 * @param Category $category
	 */
	public function removeCategory(Category $category)
	{
		$this->categories->removeElement($category);
	}

	/**
	 * @return Collection|Category[]
	 */
	public function getCategories(): ?Collection
	{
		return $this->categories;
	}

	/**
	 * @param Image $image
	 */
	public function addImage(Image $image)
	{
		$this->images[] = $image;
		$image->setTrick($this);
	}

	/**
	 * @param Image $image
	 */
	public function removeImage(Image $image)
	{
		$this->images->removeElement($image);
	}

	/**
	 * @return Collection|Image[]
	 */
	public function getImages(): ?Collection
	{
		return $this->images;
	}

	/**
	 * @param Video $video
	 */
	public function addVideo(Video $video)
	{
		$this->videos[] = $video;
		$video->setTrick($this);
	}

	/**
	 * @param Video $video
	 */
	public function removeVideo(Video $video)
	{
		$this->videos->removeElement($video);
	}

	/**
	 * @return Collection|Video[]
	 */
	public function getVideos(): ?Collection
	{
		return $this->videos;
	}

	/**
	 * @param Comment $comment
	 */
	public function addComment(Comment $comment)
	{
		$this->comments[] = $comment;
		$comment->setTrick($this);
	}

	/**
	 * @param Video $video
	 */
	public function removeComment(Comment $comment)
	{
		$this->comments->removeElement($comment);
	}

	/**
	 * @return Collection|Comment[]
	 */
	public function getComments(): ?Collection
	{
		return $this->comments;
	}

	/**
	 * @return string
	 */
	public function getThumbnail(): string
	{
		$path = '/img/thumbnail.jpg';

		if(!$this->images->isEmpty()) {
			$image = $this->images->first();
			$path = $image->getPath();
		}

		return $path;
	}
}
