<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Category;
use App\Entity\Image;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Table(name="trick")
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @UniqueEntity(fields={"name", "slug"})
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
	 * @Assert\NotBlank()
	 * @Assert\Length(max=100, maxMessage="Le titre doit faire au maximum {{limit}} caractères.")
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Length(max=100, maxMessage="Le slug doit faire au maximum {{limit}} caractères.")
	 */
	private $slug;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $description;

	/**
	 * @var Category[]|ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Category", cascade={"persist"}, inversedBy="tricks")
	 * @JoinTable(name="trick_category")
	 */
	private $categories;

	/**
	 * @var Image[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="App\Entity\Image", cascade={"persist", "remove"}, mappedBy="trick")
	 */
	private $images;

	/**
	 * @var Video[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="App\Entity\Video", cascade={"persist", "remove"}, mappedBy="trick")
	 */
	private $videos;

	public function __construct()
	{
		$this->images = new ArrayCollection();
		$this->videos = new ArrayCollection();
		$this->categories = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId(): int
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
	 * @param Category $category
	 * @return Trick
	 */
	public function addCategory(Category $category): Trick
	{
		$this->categories[] = $category;

		return $this;
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
	 * @return Trick
	 */
	public function addImage(Image $image): Trick
	{
		$this->images[] = $image;
		$image->setTrick($this);

		return $this;
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
	 * @return Trick
	 */
	public function addVideo(Video $video): Trick
	{
		$this->videos[] = $video;
		$video->setTrick($this);

		return $this;
	}

	/**
	 * @param Video $video
	 */
	public function removeVideo(Video $video)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$this->videos->removeElement($video);
	}

	/**
	 * @return Collection|Video[]
	 */
	public function getVideos(): ?Collection
	{
		return $this->videos;
	}

}
