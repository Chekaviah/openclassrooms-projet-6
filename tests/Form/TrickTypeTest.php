<?php

namespace App\Tests\Form;

use App\Entity\Trick;
use App\Form\Type\TrickType;
use App\Form\Type\VideoType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class TrickTypeTest
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class TrickTypeTest extends TypeTestCase
{
	/**
	 * @param $data
	 * @dataProvider getData
	 */
	public function testSubmitValidData($data)
	{
		//$form = $this->factory->create(TrickType::class);
	}

    /**
     * @return array
     */
	public function getData()
	{
		return array(
			array(
				'data' => array(
                    'name' => 'trick name',
                    'slug' => 'trick_slug',
                    'description' => 'trick_description',
                    'categories' => array(1, 2)
				)
			)
		);
	}
}