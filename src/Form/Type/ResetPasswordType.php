<?php

namespace App\Form\Type;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('resetToken', 	TextType::class, array(
				'label'			=> 'Token de réinitialisation'
			))
			->add('plainPassword', 	RepeatedType::class, array(
				'type' => PasswordType::class,
				'invalid_message' => 'Le mot de passe doit être identique.',
				'required' => true,
				'first_options'  => array('label' => 'Mot de passe'),
				'second_options' => array('label' => 'Confirmation du mot de passe'),
			));
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => User::class,
		));
	}
}