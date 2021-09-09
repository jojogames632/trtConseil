<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/recruiter/home", name="recruiter_home")
     */
    public function recruiterIndex()
    {
        return $this->render('home/recruiterIndex.html.twig');
    }

    /**
     * @Route("/admin/home", name="admin_home")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminIndex()
    {
        return $this->render('home/adminIndex.html.twig');
    }
}
