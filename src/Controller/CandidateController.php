<?php

namespace App\Controller;

use App\Entity\PendingJobRequest;
use App\Form\CandidateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JobRepository;
use App\Repository\PendingJobRequestRepository;
use App\Repository\ValidJobRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/candidate")
 */
class CandidateController extends AbstractController
{
    /**
     * @Route("", name="candidate_home")
     */
    public function Index(JobRepository $jobRepository, pendingJobRequestRepository $pendingJobRequestRepository, ValidJobRequestRepository $validJobRequestRepository)
    {
        $user = $this->getUser();
        $jobs = $jobRepository->findBy(['isValid' => true]);

        // create id array of all jobs requested
        $jobsIdRequested = [];
        $pendingUserJobs = $pendingJobRequestRepository->findBy(['candidate' => $user]);
        $validUserJobs = $validJobRequestRepository->findBy(['candidate' => $user]);
        if ($pendingUserJobs !== null) {
            foreach ($pendingUserJobs as $pendingUserJob) {
                $jobsIdRequested[] = $pendingUserJob->getJob()->getId();
            }
        }
        if ($validUserJobs !== null) {
            foreach ($validUserJobs as $validUserJob) {
                $jobsIdRequested[] = $validUserJob->getJob()->getId();
            }
        }
        sort($jobsIdRequested);

        // get id array of valid jobs
        $jobsId = [];
        if ($jobs !== null) {
            foreach ($jobs as $job) {
                $jobsId[] = $job->getId();
            }
            sort($jobsId);
        }

        $isAllJobsRequested = false;
        if ($jobsId === $jobsIdRequested) {
            $isAllJobsRequested = true;
        }

        return $this->render('candidate/jobs.html.twig', [
            'user' => $user,
            'jobs' => $jobs,
            'jobsIdRequested' => $jobsIdRequested,
            'isAllJobsRequested' => $isAllJobsRequested,
        ]);
    }

    /**
     * @Route("/pending-requests", name="pending_requests")
     */
    public function pendingRequests(PendingJobRequestRepository $pendingJobRequestRepository, JobRepository $jobRepository, ValidJobRequestRepository $validJobRequestRepository)
    {   
        $user = $this->getUser();
        $pendingUserJobs = $pendingJobRequestRepository->findBy(['candidate' => $user]);

        // create id array of all jobs requested
        $jobsIdRequested = [];
        $pendingUserJobs = $pendingJobRequestRepository->findBy(['candidate' => $user]);
        $validUserJobs = $validJobRequestRepository->findBy(['candidate' => $user]);
        if ($pendingUserJobs !== null) {
            foreach ($pendingUserJobs as $pendingUserJob) {
                $jobsIdRequested[] = $pendingUserJob->getJob()->getId();
            }
        }
        if ($validUserJobs !== null) {
            foreach ($validUserJobs as $validUserJob) {
                $jobsIdRequested[] = $validUserJob->getJob()->getId();
            }
        }
        sort($jobsIdRequested);

        // create array of all jobs requested
        $jobsRequested = [];
        if ($jobsIdRequested !== null) {
            foreach ($jobsIdRequested as $jobIdRequested) {
                $jobsRequested[] = $jobRepository->find($jobIdRequested);
            }
        }

        return $this->render('candidate/pendingRequests.html.twig', [
            'user' => $user,
            'jobsRequested' => $jobsRequested
        ]); 
    }

    /**
     * @Route("/valid-requests", name="valid_requests")
     */
    public function validRequests(JobRepository $jobRepository, ValidJobRequestRepository $validJobRequestRepository)
    {   
        $user = $this->getUser();
        $validUserJobs = $validJobRequestRepository->findBy(['candidate' => $user]);
        
        // create an array of valid jobs request
        $validRequests = [];
        if ($validUserJobs !== null) {
            foreach ($validUserJobs as $validUserJob) {
                $jobId = $validUserJob->getJob()->getId();
                $validRequests[] = $jobRepository->find($jobId);
            }
        }

        return $this->render('candidate/validRequests.html.twig', [
            'user' => $user,
            'validRequests' => $validRequests
        ]); 
    }

    /**
     * @Route("/postulate/{jobId<\d+>}", name="candidate_postulate")
     */
    public function postulate($jobId, JobRepository $jobRepository, EntityManagerInterface $entityManager, PendingJobRequestRepository $pendingJobRequestRepository, ValidJobRequestRepository $validJobRequestRepository)
    {
        // control non existing data
        if ($jobRepository->find($jobId) === null) {
            throw $this->createNotFoundException('Cet offre d\'emploi est introuvable');
        }

        $user = $this->getUser();
        $job = $jobRepository->find($jobId);

        // insert data in database
        $pendingJobRequest = new PendingJobRequest();
        $pendingJobRequest->setCandidate($user);
        $pendingJobRequest->setJob($job);
        $entityManager->persist($pendingJobRequest);
        $entityManager->flush();

        return $this->redirectToRoute('candidate_home');
    }

    /**
     * @Route("/edit", name="candidate_edit")
     */
    public function editProfil(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $user = $this->getUser();

        $form = $this->createForm(CandidateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cvFile = $form->get('cvFilename')->getData();
            
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $cvFile->guessExtension();

                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw $this->createAccessDeniedException('Erreur lors du téléchargement de votre fichier PDF');
                }

                $user->setCvFilename($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('candidate_home');
        }

        return $this->render('candidate/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
