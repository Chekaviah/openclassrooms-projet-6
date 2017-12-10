<?php

namespace App\DataFixtures;

use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$names = array(
			'Grabs',
			'Rotations',
			'Rotations désaxées',
			'Flips',
			'Slides',
			'One foot',
			'Old school'
		);

		foreach ($names as $name) {
			$slug = Slugger::slugify($name);

			$category = new Category();
			$category->setName($name);
			$category->setSlug($slug);

			$this->addReference('category-'.$slug, $category);

			$manager->persist($category);
		}

		$manager->flush();
	}
}