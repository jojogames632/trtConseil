<?php

namespace App\Controller;

use App\Entity\PendingJobRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JobRepository;
use App\Repository\PendingJobRequestRepository;
use App\Repository\ValidJobRequestRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/candidate")
 */
class CandidateController extends AbstractController
{
    /**
     * @Route("/", name="candidate_home")
     */
    public function Index(JobRepository $jobRepository, pendingJobRequestRepository $pendingJobRequestRepository, ValidJobRequestRepository $validJobRequestRepository)
    {
        $user = $this->getUser();
        $jobs = $jobRepository->findBy(['isValid' => true]);

        // create id array of all jobs already requested
        $jobsRequested = [];
        $pendingUserJobs = $pendingJobRequestRepository->findBy(['candidate' => $user]);
        $validUserJobs = $validJobRequestRepository->findBy(['candidate' => $user]);
        if ($pendingUserJobs !== null) {
            foreach ($pendingUserJobs as $pendingUserJob) {
                $jobsRequested[] = $pendingUserJob->getJob()->getId();
            }
        }
        if ($validUserJobs !== null) {
            foreach ($validUserJobs as $validUserJob) {
                $jobsRequested[] = $validUserJob->getJob()->getId();
            }
        }
        sort($jobsRequested);

        // get id array of valid jobs
        $jobsId = [];
        if ($jobs !== null) {
            foreach ($jobs as $job) {
                $jobsId[] = $job->getId();
            }
            sort($jobsId);
        }

        $isAllJobsRequested = false;
        if ($jobsId === $jobsRequested) {
            $isAllJobsRequested = true;
        }

        return $this->render('candidate/index.html.twig', [
            'user' => $user,
            'jobs' => $jobs,
            'jobsRequested' => $jobsRequested,
            'isAllJobsRequested' => $isAllJobsRequested
        ]);
    }

    /**
     * @Route("/postulate/{jobId}", name="candidate_postulate")
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

        $jobs = $jobRepository->findBy(['isValid' => true]);

        // create an array of id of all jobs already requested to not display them
        $jobsRequested = [];
        $pendingUserJobs = $pendingJobRequestRepository->findBy(['candidate' => $user]);
        $validUserJobs = $validJobRequestRepository->findBy(['candidate' => $user]);
        if ($pendingUserJobs !== null) {
            foreach ($pendingUserJobs as $pendingUserJob) {
                $jobsRequested[] = $pendingUserJob->getJob()->getId();
            }
        }
        if ($validUserJobs !== null) {
            foreach ($validUserJobs as $validUserJob) {
                $jobsRequested[] = $validUserJob->getJob()->getId();
            }
        }
        sort($jobsRequested);

        // get id array of valid jobs
        $jobsId = [];
        if ($jobs !== null) {
            foreach ($jobs as $job) {
                $jobsId[] = $job->getId();
            }
            sort($jobsId);
        }

        $isAllJobsRequested = false;
        if ($jobsId === $jobsRequested) {
            $isAllJobsRequested = true;
        }

        return $this->render('candidate/index.html.twig', [
            'user' => $user,
            'jobs' => $jobs,
            'jobsRequested' => $jobsRequested,
            'isAllJobsRequested' => $isAllJobsRequested
        ]);
    }
}
