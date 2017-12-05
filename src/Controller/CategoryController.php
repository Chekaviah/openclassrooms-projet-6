<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
	 * @param Request $request
     * @Route("/category/create", name="category_create")
	 * @return Response
     */
    public function createAction(Request $request)
    {
		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
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
	 * @param Category $category
	 * @Route("/category/view/{id}", requirements={"id": "\d+"}, name="category_view_id")
	 * @Route("/category/view/{slug}", name="category_view")
	 * @return Response
	 */
	public function viewAction(Category $category): Response
	{
		$tricks = $category->getTricks();

		return $this->render('category/view.html.twig', array(
			'category' => $category,
			'tricks' => $tricks
		));
	}

	/**
	 * @param Request $request
	 * @Route("/category/list", name="category_list")
	 * @return Response
	 */
	public function listAction(Request $request): Response
	{
		$categories = $this->getDoctrine()
			->getRepository(Category::class)
			->findAll();

		return $this->render('category/list.html.twig', array(
			'categories' => $categories
		));
	}

	/**
	 * @param Category $category
	 * @Route("/category/delete/{id}", requirements={"id": "\d+"}, name="category_delete")
	 * @return Response
	 */
	public function deleteAction(Request $request, Category $category): Response
	{
		$em = $this->getDoctrine()->getManager();
		/** @var Form $form */
		$form = $this->get('form.factory')->create();

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			foreach ($category->getTricks() as $trick) {
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
