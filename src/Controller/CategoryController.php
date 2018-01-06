<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Form\Type\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
	 * @param Request $request
     * @Route("/category/create", methods={"GET","POST"}, name="category_create")
	 * @return Response
     */
    public function createAction(Request $request)
    {
		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category)->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
			return $this->redirectToRoute('category_list');
		}

		return $this->render('category/create.html.twig', array(
			'form' => $form->createView()
		));
    }

	/**
	 * @param string $slug
	 * @Route("/category/view/{slug}", methods="GET", name="category_view")
	 * @return Response
	 */
	public function viewAction($slug): Response
	{
		$category = $this->getDoctrine()
			->getRepository(Category::class)
			->findOneBy(['slug' => $slug]);

		$tricks = $category->getTricks();

		return $this->render('category/view.html.twig', array(
			'category' => $category,
			'tricks' => $tricks
		));
	}

	/**
	 * @Route("/category/list", methods="GET", name="category_list")
	 * @return Response
	 */
	public function listAction(): Response
	{
		$categories = $this->getDoctrine()
			->getRepository(Category::class)
			->findAll();

		return $this->render('category/list.html.twig', array(
			'categories' => $categories
		));
	}

	/**
	 * @param int $id
	 * @Route("/category/delete/{id}", methods="GET", requirements={"id": "\d+"}, name="category_delete")
	 * @return Response
	 */
	public function deleteAction(Request $request, $id): Response
	{
		/** @var Category $category */
		$category = $this->getDoctrine()
			->getRepository(Category::class)
			->find($id);

		$em = $this->getDoctrine()->getManager();
		/** @var Form $form */
		$form = $this->get('form.factory')->create();

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			foreach ($category->getTricks() as $trick) {
				/** @var Trick $trick */
				$trick->removeCategory($category);
				$em->persist($trick);
			}
			$em->remove($category);
			$em->flush();

			$this->addFlash('info', "La catégorie a bien été supprimée.");

			return $this->redirectToRoute('category_list');
		}

		return $this->render('category/delete.html.twig', array(
			'category' => $category,
			'form' => $form->createView()
		));
	}
}
