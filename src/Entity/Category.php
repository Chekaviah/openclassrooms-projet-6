<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
	 * @ORM\Column(name="name", type="string", length=255, unique=true)
	 *
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(name="slug", type="string", length=255, unique=true)
	 */
	private $slug;

	/**
	 * @var Trick[]|ArrayCollection
	 * @ORM\ManyToMany(targetEntity="App\Entity\Trick", mappedBy="categories")
	 */
	private $tricks;

	public function __construct()
	{
		$this->tricks = new ArrayCollection();
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
	 * @param Trick $image
	 * @return Trick
	 */
	public function addTrick(Trick $trick): Category
	{
		$this->tricks[] = $trick;
		$trick->addCategory($this);

		return $this;
	}

	/**
	 * @param Trick $trick
	 */
	public function removeTrick(Trick $trick)
	{
		$this->tricks->removeElement($trick);
	}

	/**
	 * @return Collection|Trick[]
	 */
	public function getTricks(): ?Collection
	{
		return $this->tricks;
	}
}
