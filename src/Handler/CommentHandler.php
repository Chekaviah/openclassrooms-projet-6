<?php

namespace App\Handler;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class CommentHandler.
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class CommentHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CommentHandler constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     * @param Comment       $comment
     * @param User          $user
     * @param Trick         $trick
     *
     * @return bool
     */
    public function handle(FormInterface $form, Comment $comment, User $user, Trick $trick): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $comment->setUser($user);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
