<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class UserFixtures extends Fixture implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	public function load(ObjectManager $manager)
	{
		$passwordEncoder = $this->container->get('security.password_encoder');

		$admin = new User();
		$admin->setUsername('admin');
		$admin->setEmail('admin@website.net');
		$admin->setRoles(['ROLE_ADMIN']);
		$admin->setPassword($passwordEncoder->encodePassword($admin, 'admin'));
        $admin->setActive(true);
		$manager->persist($admin);

		$user = new User();
		$user->setUsername('user');
		$user->setEmail('user@website.net');
		$user->setPassword($passwordEncoder->encodePassword($admin, 'user'));
        $user->setActive(true);
		$manager->persist($user);

		$manager->flush();
	}
}