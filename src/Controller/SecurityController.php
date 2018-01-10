<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
	 * @Route("/register", methods={"GET", "POST"}, name="security_register")
	 * @return Response
	 */
	public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($password);

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

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
}
