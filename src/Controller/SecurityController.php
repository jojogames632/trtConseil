<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {    
        $roles = [];
        // get roles if user is connected
        if ($this->getUser()) {
            $roles = $this->getUser()->getRoles();
        }
        // if user is already connected, move to home page
        if (in_array('ROLE_RECRUITER', $roles)) {
            return $this->redirectToRoute('recruiter_home');
        }
        else if (in_array('ROLE_CANDIDATE', $roles)) {
            return $this->redirectToRoute('candidate_home');
        }
        else if (in_array('ROLE_CONSULTANT', $roles)) {
            return $this->redirectToRoute('consultant_home');
        }
        else if (in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('admin_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
