<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class TrickEditType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('videos', 		CollectionType::class, array(
				'entry_type'	=>	VideoType::class,
				'allow_add'		=> 	true,
				'allow_delete'	=> 	true,
				'by_reference'	=>	false
			));
	}

	public function getParent()
	{
		return TrickType::class;
	}
}
