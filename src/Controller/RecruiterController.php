<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\AdType;
use App\Form\RecruiterType;
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
     * @Route("", name="recruiter_home")
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

        return $this->render('recruiter/candidatesList.html.twig', [
            'user' => $user,
            'validJobRequests' => $validJobRequests,
        ]);
    }

    /**
     * @Route("/create-job", name="create_job")
     */
    public function createJob(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $this->getUser();

        $job = new Job();
        $form = $this->createForm(AdType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setIsValid(false);
            $job->setRecruiter($user);

            $entityManager->persist($job);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce est en attente de validation et sera publiée prochainement');

            return $this->redirectToRoute('create_job');
        }
        
        $errors = $validator->validate($job);

        return $this->render('recruiter/createJob.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'errors' => $errors
        ]); 
    }
    
    /**
     * @Route("/edit", name="recruiter_edit")
     */
    public function editProfil(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $form = $this->createForm(RecruiterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('recruiter_home');
        }

        return $this->render('recruiter/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
