<?php

namespace App\Controller;


use App\Entity\User;
use App\Event\UserCreatedEvent;
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
			$user->setToken(bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)));

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
	 * @param Request $request
	 * @param string $token
	 * @Route("/confirm/{token}", methods={"GET"}, name="security_confirm")
	 * @return Response
	 */
	public function confirmAction(string $token)
	{
		$user = $this->getDoctrine()
			->getRepository(User::class)
			->findOneBy(['token' => $token]);

		if(!$user)
			throw $this->createNotFoundException('Ce token n\'est pas valide');

		$em = $this->getDoctrine()->getManager();
		$user->setToken(null);
		$user->setActive(true);
		$em->flush();

		$this->addFlash('success', "Votre compte est validé, vous pouvez vous connecter.");

		return $this->redirectToRoute('security_login');
	}

	/**
	 * @throws \Exception
	 * @Route("/logout", name="security_logout")
	 */
	public function logout()
	{
		throw new \Exception('This should never be reached!');
	}
}
