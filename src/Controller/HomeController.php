<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use function PHPUnit\Framework\isEmpty;

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
     * @Route("/candidate/home", name="candidate_home")
     * 
     * @IsGranted("ROLE_CANDIDATE")
     */
    public function candidateIndex()
    {
        return $this->render('home/candidateIndex.html.twig');
    }

    /**
     * @Route("/consultant/home", name="consultant_home")
     * 
     * @IsGranted("ROLE_CONSULTANT")
     */
    public function consultantIndex()
    {
        return $this->render('home/consultantIndex.html.twig');
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
