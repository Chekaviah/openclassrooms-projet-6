<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\Video;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick", name="trick")
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
	 * @param Trick $trick
	 *
	 * @Route("/trick/{id}", name="trick_view")
	 * @return Response
	 */
    public function viewAction(Trick $trick): Response
	{
		return new Response($trick->getDescription());
	}
}
