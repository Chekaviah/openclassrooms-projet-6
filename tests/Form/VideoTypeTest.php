<?php

namespace App\Tests\Form;

use App\Entity\Video;
use App\Form\Type\VideoType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class VideoTypeTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
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

    /**
     * @return array
     */
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