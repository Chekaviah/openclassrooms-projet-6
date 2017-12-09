<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VideoFixtures extends Fixture
{
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$videos = array(
			array(
				'url' => 't8IXNXx68PU',
				'website' => 'youtube',
				'trick' => 'bs-540-seatbelt'
			),
			array(
				'url' => 'fYdMFx12zvI',
				'website' => 'youtube',
				'trick' => 'bs-540-seatbelt'
			),
			array(
				'url' => 'KDgwOuzJtjo',
				'website' => 'youtube',
				'trick' => 'backside-triple-cork-1440'
			),
			array(
				'url' => 'URFnYGzu9lU',
				'website' => 'youtube',
				'trick' => 'backside-triple-cork-1440'
			),
			array(
				'url' => 'xxxu60',
				'website' => 'dailymotion',
				'trick' => 'backside-triple-cork-1440'
			)
		);

		foreach ($videos as $v) {
			$video = new Video();
			$video->setUrl($v['url']);
			$video->setWebsite($v['website']);

			/** @var Trick $trick */
			$trick = $this->getReference('trick-'.$v['trick']);
			$video->setTrick($trick);

			$manager->persist($video);
		}

		$manager->flush();
	}

	public function getDependencies()
	{
		return array(
			TrickFixtures::class,
		);
	}
}