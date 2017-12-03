<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\TrickType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick", name="trick")
	 * @return Response
     */
    public function index(): Response
    {
    	$em = $this->getDoctrine()->getManager();

		$category = new Category();
		$category->setName("Categorie2");
		$category->setSlug("cat2");

		$video = new Video();
		$video->setUrl("https://www.youtube.com/watch?v=JFalY7wXm-IF");

		$trick = new Trick();
		$trick->setName("Test3");
		$trick->setSlug("test3");
		$trick->setDescription("Test de trick - troisième");

		$trick->addCategory($category);
		$trick->addVideo($video);

		$em->persist($trick);

		$em->flush();

        return new Response('Save new trick with id '.$trick->getId());
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
			return $this->redirectToRoute('trick_view', array('id' => $trick->getId()));
		}

		return $this->render('trick/create.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @param Trick $trick
	 * @Route("/trick/{id}", name="trick_view")
	 * @return Response
	 */
    public function viewAction(Trick $trick): Response
	{
		return new Response($trick->getDescription());
	}
}
