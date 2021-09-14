<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/consultant")
 */
class ConsultantController extends AbstractController
{
    /**
     * @Route("", name="consultant_home")
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $pendingCandidates = $userRepository->findInactivatedUsersByRole("ROLE_CANDIDATE");
        $pendingRecruiters = $userRepository->findInactivatedUsersByRole("ROLE_RECRUITER");

        return $this->render('consultant/index.html.twig', [
            'user' => $user,
            'pendingCandidates' => $pendingCandidates,
            'pendingRecruiters' => $pendingRecruiters
        ]);
    }

    /**
     * @Route("/activateRecruiter/{id}", name="activate_recruiter")
     */
    public function activateRecruiter($id, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {  
        // redirect to 404 if user not found
        if (!$userRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('Le recruteur avec l\id %s n\'existe pas', $id));
        }

        $recruiter = $userRepository->find($id);
        $recruiter->setIsActive(true);
        $entityManager->persist($recruiter);
        $entityManager->flush();

        return $this->redirectToRoute('consultant_home');
    }

    /**
     * @Route("/activateCandidate/{id}", name="activate_candidate")
     */
    public function activateCandidate($id, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {  
        // redirect to 404 if user not found
        if (!$userRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('Le candidat avec l\id %s n\'existe pas', $id));
        }

        $candidate = $userRepository->find($id);
        $candidate->setIsActive(true);
        $entityManager->persist($candidate);
        $entityManager->flush();

        return $this->redirectToRoute('consultant_home');
    }
}
