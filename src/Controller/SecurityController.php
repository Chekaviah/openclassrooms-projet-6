<?php

namespace App\Controller;


use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Event\UserResetPasswordEvent;
use App\Form\Type\LostPasswordType;
use App\Form\Type\ResetPasswordType;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/login", methods={"GET", "POST"}, name="security_login")
	 * @return Response
	 */
	public function loginAction(AuthenticationUtils $authUtils): Response
	{
		$error = $authUtils->getLastAuthenticationError();

		$lastUsername = $authUtils->getLastUsername();

		return $this->render('Security/login.html.twig', array(
			'last_username' => $lastUsername,
			'error'			=> $error
		));
	}

	/**
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param EventDispatcherInterface $dispatcher
	 * @Route("/register", methods={"GET", "POST"}, name="security_register")
	 * @return Response
	 */
	public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $dispatcher): Response
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($password);
			$user->setConfirmationToken(bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)));

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			$event = new UserCreatedEvent($user);
			$dispatcher->dispatch(UserCreatedEvent::NAME, $event);

			return $this->redirectToRoute('security_login');
		}

		return $this->render('Security/register.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @throws \Exception
	 * @Route("/logout", name="security_logout")
	 */
	public function logout()
	{
		throw new \Exception('This should never be reached!');
	}

	/**
	 * @param Request $request
	 * @param string $token
	 * @Route("/confirm/{token}", methods={"GET"}, name="security_confirm")
	 * @return Response
	 */
	public function confirmAction(string $token)
	{
		$user = $this->getDoctrine()
			->getRepository(User::class)
			->findOneBy(['confirmationToken' => $token]);

		if(!$user)
			throw $this->createNotFoundException('Ce token n\'est pas valide');

		$em = $this->getDoctrine()->getManager();
		$user->setConfirmationToken(null);
		$user->setActive(true);
		$em->flush();

		$this->addFlash('success', "Votre compte est validé, vous pouvez vous connecter.");

		return $this->redirectToRoute('security_login');
	}

	/**
	 * @param Request $request
	 * @param EventDispatcherInterface $dispatcher
	 * @Route("/lost-password", methods={"GET", "POST"}, name="security_lost_password")
	 * @return Response
	 */
	public function lostPasswordAction(Request $request, EventDispatcherInterface $dispatcher)
	{
		$userForm = new User();
		$form = $this->createForm(LostPasswordType::class, $userForm)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getDoctrine()
				->getRepository(User::class)
				->findOneBy(['email' => $userForm->getEmail()]);

			if ($user) {
				$user->setResetToken(bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)));

				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();

				$event = new UserResetPasswordEvent($user);
				$dispatcher->dispatch(UserResetPasswordEvent::NAME, $event);
			}

			return $this->redirectToRoute('security_reset_password');
		}

		return $this->render('Security/lost-password.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @param Request $request
	 * @Route("/reset-password", methods={"GET", "POST"}, name="security_reset_password")
	 * @return Response
	 */
	public function resetPasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		$userForm = new User();
		$form = $this->createForm(ResetPasswordType::class, $userForm)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getDoctrine()
				->getRepository(User::class)
				->findOneBy(['resetToken' => $userForm->getResetToken()]);

			if(!$user)
				throw $this->createNotFoundException('Ce token n\'est pas valide');

			$password = $passwordEncoder->encodePassword($user, $userForm->getPlainPassword());
			$user->setPassword($password);
			$user->setResetToken(null);

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			$this->addFlash('success', "Votre mot de passe a été changé, vous pouvez vous connecter.");

			return $this->redirectToRoute('security_login');
		}

		return $this->render('Security/lost-password.html.twig', array(
			'form' => $form->createView()
		));
	}
}
