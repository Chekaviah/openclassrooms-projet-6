<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Type\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends AbstractController
{
	/**
	 * @param Request $request
	 * @Route("/profile", methods={"GET", "POST"}, name="user_profile")
	 * @return Response
	 */
	public function profileAction(Request $request): Response
	{
		$user = $this->getUser();

		$form = $this->createForm(ProfileType::class, $user)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$em->persist($user);
			$em->flush();
			return $this->redirectToRoute('user_profile');
		}

		return $this->render('User/profile.html.twig', array(
			'form' => $form->createView()
		));
	}
}
