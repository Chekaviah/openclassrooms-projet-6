<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\Type\TrickType;
use App\Form\Type\CommentType;
use App\Handler\TrickEditHandler;
use App\Handler\TrickCreateHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class TrickController
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/", methods="GET", name="trick_list")
     *
     * @return Response
     */
    public function listAction(): Response
    {
        $tricks = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findAllWithData();

        return $this->render('Trick/list.html.twig', array(
            'tricks' => $tricks
        ));
    }

    /**
     * @param string $slug
     *
     * @Route("/trick/view/{slug}", methods="GET", name="trick_view")
     *
     * @return Response
     */
    public function viewAction(string $slug): Response
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBySlugWithData($slug);

        if(!$trick)
            throw $this->createNotFoundException();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('Trick/view.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request            $request
     * @param TrickCreateHandler $trickCreateHandler
     *
     * @Route("/trick/create", methods={"GET","POST"}, name="trick_create")
     *
     * @return Response
     */
    public function createAction(Request $request, TrickCreateHandler $trickCreateHandler): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($trickCreateHandler->handle($form, $trick))
            return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));

        return $this->render('Trick/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param TrickEditHandler $trickEditHandler
     *
     * @Route("/trick/edit/{id}", methods={"GET","POST"}, requirements={"id": "\d+"}, name="trick_edit")
     *
     * @return Response
     */
    public function editAction(Request $request, TrickEditHandler $trickEditHandler, int $id): Response
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneByIdWithData($id);

        if(!$trick)
            throw $this->createNotFoundException();

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($trickEditHandler->handle($form, $trick))
            return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));

        return $this->render('Trick/edit.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView()
        ));
    }

    /**
     * @param int $id
     * @param Request $request
     *
     * @Route("/trick/delete/{id}", methods="POST", requirements={"id": "\d+"}, name="trick_delete")
     *
     * @return Response
     */
    public function deleteAction(Request $request, $id): Response
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($id);

        if(!$trick)
            throw $this->createNotFoundException();

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token')))
            return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));

        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();

        $this->addFlash('success', "Le trick a bien été supprimé.");

        return $this->redirectToRoute('trick_list');
    }
}
