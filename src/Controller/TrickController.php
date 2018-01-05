<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\TrickEditType;
use App\Form\TrickType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
	/**
	 * @Route("/", methods="GET", name="trick_list")
	 * @return Response
	 */
    public function listAction(): Response
	{
		$tricks = $this->getDoctrine()
			->getRepository(Trick::class)
			->findAll();

		return $this->render('trick/list.html.twig', array(
			'tricks' => $tricks
		));
	}

	/**
	 * @param string $slug
	 * @Route("/trick/view/{slug}", methods="GET", name="trick_view")
	 * @return Response
	 */
	public function viewAction( $slug): Response
	{
		$trick = $this->getDoctrine()
			->getRepository(Trick::class)
			->findOneBy(['slug' => $slug]);

		$comment = new Comment();
		$form = $this->createForm(CommentType::class, $comment);

		return $this->render('trick/view.html.twig', array(
			'trick' => $trick,
			'form' => $form->createView()
		));
	}

	/**
	 * @param Request $request
	 * @Route("/trick/create", methods={"GET","POST"}, name="trick_create")
	 * @return Response
	 */
	public function createAction(Request $request): Response
	{
		$trick = new Trick();
		$form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($trick);
			$em->flush();
			return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
		}

		return $this->render('trick/create.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @param int $id
	 * @param Request $request
	 * @Route("/trick/edit/{id}", methods={"GET","POST"}, requirements={"id": "\d+"}, name="trick_edit")
	 * @return Response
	 */
	public function editAction(Request $request, $id): Response
	{
		$trick = $this->getDoctrine()
			->getRepository(Trick::class)
			->find($id);

		$form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$em->persist($trick);
			$em->flush();
			return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
		}

		return $this->render('trick/edit.html.twig', array(
			'trick' => $trick,
			'form' => $form->createView()
		));
	}

	/**
	 * @param int $id
	 * @param Request $request
	 * @Route("/trick/delete/{id}", methods="POST", requirements={"id": "\d+"}, name="trick_delete")
	 * @return Response
	 */
	public function deleteAction(Request $request, $id): Response
	{
		$trick = $this->getDoctrine()
			->getRepository(Trick::class)
			->find($id);

		if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
			return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($trick);
		$em->flush();

		$this->addFlash('success', "Le trick a bien été supprimé.");

		return $this->redirectToRoute('trick_list');
	}
}
