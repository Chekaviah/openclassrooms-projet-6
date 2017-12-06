<?php

namespace App\Tests\Form;


use App\Entity\Video;
use App\Form\VideoType;
use Symfony\Component\Form\Test\TypeTestCase;

class VideoTypeTest extends TypeTestCase
{
	/**
	 * @param $data
	 * @dataProvider getData
	 */
	public function testSubmitValidData($data)
	{
		$form = $this->factory->create(VideoType::class);

		$object = new Video();
		if(isset($data['url']))
			$object->setUrl($data['url']);
		if(isset($data['website']))
			$object->setWebsite($data['website']);

		$form->submit($data);

		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($object, $form->getData());

		$view = $form->createView();
		$children = $view->children;

		foreach (array_keys($data) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}

	public function getData()
	{
		return array(
			array(
				'data' => array(
					'url' => 'videoid',
					'website' => 'youtube'
				)
			),
			array(
				'data' => array(
					'url' => 'videoid',
					'website' => 'dailymotion'
				)
			),
			array(
				'data' => array()
			),
			array(
				'data' => array(
					'url' => null,
					'website' => null
				)
			)
		);
	}
}