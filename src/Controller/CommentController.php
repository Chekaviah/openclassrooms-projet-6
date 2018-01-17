<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommentController extends AbstractController
{
    /**
     * @param Request $request
     * @param string $slug
     * @Route("/trick/{slug}/comment", methods={"POST"}, name="comment_add")
     * @return Response
     */
    public function commentAction(Request $request, $slug): Response
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);

        $user = $this->getUser();

        $comment = new Comment();
        $comment->setTrick($trick);
        $comment->setUser($user);

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
    }

    /**
     * @param Request $request
     * @param int $id
     * @Route("/comment/{id}", requirements={"id": "\d+"}, methods={"POST"}, name="comment_delete")
     * @return Response
     */
    public function deleteAction(Request $request, $id): Response
    {
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);

        if (!$this->isCsrfTokenValid('comment_delete', $request->request->get('token')))
            return $this->redirectToRoute('trick_view', array('slug' => $comment->getTrick()->getSlug()));

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('trick_view', array('slug' => $comment->getTrick()->getSlug()));
    }
}
