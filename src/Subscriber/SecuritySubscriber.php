<?php

namespace App\Subscriber;


use App\Event\UserCreatedEvent;
use App\Event\UserResetPasswordEvent;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class SecuritySubscriber implements EventSubscriberInterface
{
	/**
	 * @var Environment
	 */
	private $twig;

	/**
	 * @var Swift_Mailer
	 */
	private $mailer;

	public function __construct(
		Environment $twig,
		Swift_Mailer $mailer
	) {
		$this->twig = $twig;
		$this->mailer = $mailer;
	}

	public static function getSubscribedEvents()
	{
		return array(
			UserCreatedEvent::NAME => 'onUserCreated',
			UserResetPasswordEvent::NAME => 'onUserResetPassword',
		);
	}

	public function onUserCreated(UserCreatedEvent $event)
	{
		$message = (new Swift_Message('Votre compte a bien été créé !'))
			->setFrom('noreply@snowtricks.com')
			->setTo($event->getUser()->getEmail())
			->setBody(
				$this->twig->render('Email/registration.html.twig', ['user' => $event->getUser()]),
				'text/html'
			);

		$this->mailer->send($message);
	}

	public function onUserResetPassword(UserResetPasswordEvent $event)
	{
		$message = (new Swift_Message('Réinitialisation du mot de passe'))
			->setFrom('noreply@snowtricks.com')
			->setTo($event->getUser()->getEmail())
			->setBody(
				$this->twig->render('Email/lost-password.html.twig', ['user' => $event->getUser()]),
				'text/html'
			);

		$this->mailer->send($message);
	}
}
