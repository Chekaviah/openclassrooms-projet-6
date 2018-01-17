<?php

namespace App\Handler;


use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordHandler
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
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * ResetPasswordHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ManagerRegistry $managerRegistry
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->managerRegistry = $managerRegistry;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param FormInterface $form
     * @param User $user
     * @return bool
     */
    public function handle(FormInterface $form, User $userForm): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->managerRegistry
                ->getRepository(User::class)
                ->findOneBy(['resetToken' => $userForm->getResetToken()]);

            if ($user) {
                $password = $this->passwordEncoder->encodePassword($user, $userForm->getPlainPassword());
                $user->setPassword($password);
                $user->setResetToken(null);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return true;
            }
        }

        return false;
    }
}