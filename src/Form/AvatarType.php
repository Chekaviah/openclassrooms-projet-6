<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('file', FileType::class)
			->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
				$avatar = $event->getData();
				$form = $event->getForm();

				if($avatar && $avatar->getId() !== null) {
					$form->add('name', TextType::class);
					$form->add('extension', TextType::class);
					$form->remove('file');
				}
			})
		;

	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Avatar::class,
		]);
	}
}
