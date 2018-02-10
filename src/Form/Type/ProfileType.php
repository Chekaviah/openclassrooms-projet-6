<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', 	TextType::class, array(
				'label' => 'Nom d\'utilisateur'
			))
			->add('email', 		EmailType::class)
			->add('avatar', 	AvatarType::class, array(
                'required' => false
            ))
		;
	}

    /**
     * @param OptionsResolver $resolver
     */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
			'csrf_protection' => true
		]);
	}
}
