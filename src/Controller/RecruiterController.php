<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\AdType;
use App\Repository\JobRepository;
use App\Repository\UserRepository;
use App\Repository\ValidJobRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/recruiter")
 */
class RecruiterController extends AbstractController
{
    /**
     * @Route("/home", name="recruiter_home")
     */
    public function Index(ValidJobRequestRepository $validJobRequestRepository, JobRepository $jobRepository, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $validJobRequests = [];

        $jobs = $jobRepository->findBy([
            'recruiter' => $userId,
            'isValid' => true
        ]);
        
        if (count($jobs) > 0) {
            foreach ($jobs as $job) {
                $jobId = $job->getId(); 
                $validJobRequests[] = $validJobRequestRepository->findBy(['job' => $jobId]);
            }
        }
        
        $job = new Job();
        $form = $this->createForm(AdType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setIsValid(false);
            $job->setRecruiter($user);

            $entityManager->persist($job);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce est en attente de validation et sera publiée prochainement');

            return $this->redirectToRoute('recruiter_home');
        }
        
        $errors = $validator->validate($job);

        return $this->render('recruiter/index.html.twig', [
            'user' => $user,
            'validJobRequests' => $validJobRequests,
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }
    
    /**
     * @Route("/edit", name="recruiter_edit")
     */
    public function editProfil(UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();
        $userId = $currentUser->getId();
        $user = $userRepository->find($userId);

        return $this->render('recruiter/edit.html.twig', [
           'user' => $user
        ]); 
    }

    /**
     * @Route("/update", name="recruiter_update", methods={"POST"})
     */
    public function updateProfil(Request $request, EntityManagerInterface $entityManager)
    {
        $company = htmlspecialchars($request->request->get('company'));
        $address = htmlspecialchars($request->request->get('address'));

        $user = $this->getUser();
        $user->setCompany($company);
        $user->setAddress($address);
        $entityManager->flush();

        return $this->redirectToRoute('recruiter_home');
    }
}
