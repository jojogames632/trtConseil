<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\TrtConseilAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * @Route("/register")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/candidate", name="candidate_register")
     */
    public function candidateRegister(Request $request, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $authenticator, TrtConseilAuthenticator $formAuthenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setIsActive(false);
            $user->setRoles(["ROLE_CANDIDATE"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $authenticator->authenticateUser($user, $formAuthenticator, $request); 
        }

        return $this->render('registration/candidateRegister.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recruiter", name="recruiter_register")
     */
    public function recruiterRegister(Request $request, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $authenticator, TrtConseilAuthenticator $formAuthenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setIsActive(false);
            $user->setRoles(["ROLE_RECRUITER"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $authenticator->authenticateUser($user, $formAuthenticator, $request);
        }

        return $this->render('registration/recruiterRegister.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
