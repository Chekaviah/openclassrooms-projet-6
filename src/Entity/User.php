<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
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
	 * @ORM\Column(type="string", length=25, unique=true)
	 */
	private $username;

	/**
	 * @var string
	 */
	private $plainPassword;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=64)
	 */
	private $password;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=60, unique=true)
	 */
	private $email;

	/**
	 * @var boolean
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive = false;

	/**
	 * @var array
	 * @ORM\Column(name="roles", type="json")
	 */
	private $roles = [];

	/**
	 * @var string
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	private $token;

	/**
	 * @var Avatar
	 * @ORM\OneToOne(targetEntity="App\Entity\Avatar", cascade={"persist", "remove", "refresh"}, fetch="EAGER", orphanRemoval=true)
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $avatar;

	public function __construct()
	{
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
	public function getUsername(): ?string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getPlainPassword(): ?string
	{
		return $this->plainPassword;
	}

	/**
	 * @param string $plainPassword
	 */
	public function setPlainPassword($plainPassword)
	{
		$this->plainPassword = $plainPassword;
	}

	/**
	 * @return string
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return boolean
	 */
	public function getActive()
	{
		return $this->isActive;
	}

	/**
	 * @param boolean $isActive
	 */
	public function setActive($isActive)
	{
		$this->isActive = $isActive;
	}

	/**
	 * @return array
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;

		if (empty($roles))
			$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	/**
	 * @param array $roles
	 */
	public function setRoles(array $roles)
	{
		$this->roles = $roles;
	}

	/**
	 * @return string
	 */
	public function getToken(): ?string
	{
		return $this->token;
	}

	/**
	 * @param string $token
	 */
	public function setToken($token)
	{
		$this->token = $token;
	}

	/**
	 * @return string
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * @return Avatar
	 */
	public function getAvatar(): ?Avatar
	{
		return $this->avatar;
	}

	/**
	 * @param Avatar $avatar
	 */
	public function setAvatar($avatar)
	{
		$this->avatar = $avatar;
	}

	/**
	 * @return string
	 */
	public function getAvatarPath(): ?string
	{
		$path = '/img/avatar.jpg';

		if(!is_null($this->avatar))
			$path = $this->avatar->getPath();

		return $path;
	}

	/**
	 *
	 */
	public function eraseCredentials()
	{
	}

	/**
	 * @return string
	 */
	public function serialize(): string
	{
		return serialize(array(
			$this->id,
			$this->username,
			$this->password,
			$this->isActive
		));
	}

	/**
	 * @param $serialized
	 */
	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->username,
			$this->password,
			$this->isActive
			) = unserialize($serialized);
	}

	/**
	 * @return bool
	 */
	public function isAccountNonExpired()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function isAccountNonLocked()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function isCredentialsNonExpired()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->isActive;
	}
}
