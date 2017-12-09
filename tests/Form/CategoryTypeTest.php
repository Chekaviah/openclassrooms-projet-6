<?php

namespace App\Tests\Form;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

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
				'date' => array(
					'name' => null,
					'slug' => null
				)
			)
		);
	}
}