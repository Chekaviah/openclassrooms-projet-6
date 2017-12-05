<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\Video;
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
     * @Route("/", name="trick")
	 * @return Response
     */
    public function index(): Response
    {
        return new Response('Hello world!');
    }

	/**
	 * @param Request $request
	 * @Route("/trick/list", name="trick_list")
	 * @return Response
	 */
    public function listAction(Request $request): Response
	{
		$tricks = $this->getDoctrine()
			->getRepository(Trick::class)
			->findAll();

		return $this->render('trick/list.html.twig', array(
			'tricks' => $tricks
		));
	}

	/**
	 * @param Trick $trick
	 * @Route("/trick/{id}", requirements={"id": "\d+"}, name="trick_view_id")
	 * @Route("/trick/{slug}", name="trick_view")
	 * @return Response
	 */
	public function viewAction(Trick $trick): Response
	{
		return $this->render('trick/view.html.twig', array(
			'trick' => $trick
		));
	}

	/**
	 * @param Request $request
	 * @Route("/trick/create", name="trick_create")
	 * @return Response
	 */
	public function createAction(Request $request): Response
	{
		$trick = new Trick();
		$form = $this->createForm(TrickType::class, $trick);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
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
	 * @param Request $request
	 * @param Trick $trick
	 * @Route("/trick/edit/{id}", name="trick_edit")
	 * @return Response
	 */
	public function editAction(Request $request, Trick $trick): Response
	{
		$originalVideos = new ArrayCollection();
		foreach ($trick->getVideos() as $video) {
			$originalVideos->add($video);
		}

		$originalImages = new ArrayCollection();
		foreach ($trick->getImages() as $image) {
			$originalImages->add($image);
		}


		$form = $this->createForm(TrickEditType::class, $trick);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();

			// Delete videos
			foreach ($originalVideos as $video) {
				if ($trick->getVideos()->contains($video) === false) {
					$em->remove($video);
				}
			}

			// Add Videos
			foreach ($trick->getVideos() as $video) {
				if ($video->getTrick() === null)
					$video->setTrick($trick);
			}

			// Delete images
			foreach ($originalImages as $image) {
				if ($trick->getImages()->contains($image) === false) {
					$em->remove($image);
				}
			}

			// Add images
			foreach ($trick->getImages() as $image) {
				if ($image->getTrick() === null)
					$image->setTrick($trick);
			}

			$em->persist($trick);
			$em->flush();
			return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
		}

		return $this->render('trick/edit.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @param Trick $trick
	 * @Route("/trick/delete/{id}", requirements={"id": "\d+"}, name="trick_delete")
	 * @return Response
	 */
	public function deleteAction(Request $request, Trick $trick): Response
	{
		$em = $this->getDoctrine()->getManager();
		/** @var Form $form */
		$form = $this->get('form.factory')->create();

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->remove($trick);
			$em->flush();

			$this->addFlash('info', "Le trick a bien été supprimé.");

			return $this->redirectToRoute('trick_list');
		}

		return $this->render('trick/delete.html.twig', array(
			'trick' => $trick,
			'form' => $form->createView()
		));
	}
}
