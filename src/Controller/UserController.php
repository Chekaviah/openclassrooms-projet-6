<?php

namespace App\Controller;


use App\Form\Type\ProfileType;
use App\Handler\ProfileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserController
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class UserController extends AbstractController
{
    /**
     * @param Request        $request
     * @param ProfileHandler $profileHandler
     *
     * @Route("/profile", methods={"GET", "POST"}, name="user_profile")
     *
     * @return Response
     */
    public function profileAction(Request $request, ProfileHandler $profileHandler): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user)->handleRequest($request);

        if($profileHandler->handle($form, $user)) {
            $this->addFlash('success', "Votre profil a bien été mis à jour.");
            return $this->redirectToRoute('user_profile');
        }

        return $this->render('User/profile.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
