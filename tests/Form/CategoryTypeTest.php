<?php

namespace App\Tests\Form;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class CategoryTypeTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class CategoryTypeTest extends TypeTestCase
{
	/**
	 * @param $data
	 * @dataProvider getData
	 */
	public function testSubmitValidData($data)
	{
		$form = $this->factory->create(CategoryType::class);

		$object = new Category();
		if(isset($data['name']))
			$object->setName($data['name']);
		if(isset($data['slug']))
			$object->setSlug($data['slug']);

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
					'name' => 'category_name',
					'slug' => 'category_slug'
				)
			),
			array(
				'data' => array()
			),
			array(
				'data' => array(
					'name' => null,
					'slug' => null
				)
			)
		);
	}
}