<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class LostPasswordHandler
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class LostPasswordHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * LostPasswordHandler constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $entityManager;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param FormInterface $form
     * @param User $userForm
     *
     * @return bool
     */
    public function handle(FormInterface $form, User $userForm)
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->managerRegistry
                ->getRepository(User::class)
                ->findOneBy(['email' => $userForm->getEmail()]);

            if ($user) {
                $user->setResetToken(bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)));

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            }
        }

        return false;
    }
}