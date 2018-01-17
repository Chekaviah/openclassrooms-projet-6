<?php

namespace App\Controller;


use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Event\UserResetPasswordEvent;
use App\Form\Type\LostPasswordType;
use App\Form\Type\ResetPasswordType;
use App\Form\Type\UserType;
use App\Handler\LostPasswordHandler;
use App\Handler\RegisterHandler;
use App\Handler\ResetPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/**
     * @param AuthenticationUtils $authUtilss
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
	 * @param RegisterHandler $registerHandler
	 * @param EventDispatcherInterface $dispatcher
	 * @Route("/register", methods={"GET", "POST"}, name="security_register")
	 * @return Response
	 */
	public function registerAction(Request $request, RegisterHandler $registerHandler, EventDispatcherInterface $dispatcher): Response
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($registerHandler->handle($form, $user)) {
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
	 * @param Request
     * @param LostPasswordHandler $lostPasswordHandler
	 * @param EventDispatcherInterface $dispatcher
	 * @Route("/lost-password", methods={"GET", "POST"}, name="security_lost_password")
	 * @return Response
	 */
	public function lostPasswordAction(Request $request, LostPasswordHandler $lostPasswordHandler, EventDispatcherInterface $dispatcher)
	{
		$user = new User();
		$form = $this->createForm(LostPasswordType::class, $user)->handleRequest($request);

        if ($lostPasswordHandler->handle($form, $user)) {
            $event = new UserResetPasswordEvent($user);
            $dispatcher->dispatch(UserResetPasswordEvent::NAME, $event);

			return $this->redirectToRoute('security_reset_password');
		}

		return $this->render('Security/lost-password.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @param Request $request
     * @param ResetPasswordHandler $resetPasswordHandler
	 * @Route("/reset-password", methods={"GET", "POST"}, name="security_reset_password")
	 * @return Response
	 */
	public function resetPasswordAction(Request $request, ResetPasswordHandler $resetPasswordHandler)
	{
		$user = new User();
		$form = $this->createForm(ResetPasswordType::class, $user)->handleRequest($request);

        if ($resetPasswordHandler->handle($form, $user)) {
            $this->addFlash('success', "Votre mot de passe a été changé, vous pouvez vous connecter.");
            return $this->redirectToRoute('security_login');
        }

		return $this->render('Security/lost-password.html.twig', array(
			'form' => $form->createView()
		));
	}
}
