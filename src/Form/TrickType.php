<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 			TextType::class)
			->add('slug', 			TextType::class)
			->add('description', 	TextareaType::class)
			->add('categories', 	EntityType::class, array(
				'class'			=>	'App\Entity\Category',
				'choice_label'	=>	'name',
				'multiple'		=>	true
			))
			->add('images',			CollectionType::class, array(
				'entry_type'	=>	ImageType::class,
				'allow_add'		=>	true,
				'allow_delete'	=>	true,
				'by_reference'	=> 	false
			))
			->add('videos', 		CollectionType::class, array(
				'entry_type'	=>	VideoType::class,
				'allow_add'		=> 	true,
				'allow_delete'	=> 	true,
				'by_reference'	=> 	false
			))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
			'csrf_protection' => true
        ]);
    }
}
