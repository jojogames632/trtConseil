<?php

namespace App\Controller;

use App\Entity\ValidJobRequest;
use App\Repository\JobRepository;
use App\Repository\PendingJobRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

        return $this->render('consultant/activeAccounts.html.twig', [
            'user' => $user,
            'pendingCandidates' => $pendingCandidates,
            'pendingRecruiters' => $pendingRecruiters,
        ]);
    }

    /**
     * @Route("/valid-jobs", name="valid_jobs")
     */
    public function validJobs(JobRepository $jobRepository)
    {
        $user = $this->getUser();

        $invalidJobs = $jobRepository->findBy(['isValid' => false]);

        return $this->render('consultant/validJobs.html.twig', [
            'user' => $user,
            'invalidJobs' => $invalidJobs
        ]);
    }

    /**
     * @Route("/valid-postulations", name="valid_postulations")
     */
    public function validPostulations(JobRepository $jobRepository, PendingJobRequestRepository $pendingJobRequestRepository)
    {
        $user = $this->getUser();

        $pendingJobRequests = $pendingJobRequestRepository->findAll();

        // create an array of unique job id from pending job requests
        $pendingJobsId = [];
        foreach ($pendingJobRequests as $pendingJobRequest) {
            $pendingJobsId[] = $pendingJobRequest->getJob()->getId();
        }
        $pendingJobsId = array_unique($pendingJobsId);

        // create a double array which contains job associated with his candidates from pending job requests
        $requests = [];
        foreach ($pendingJobsId as $pendingJobId) {
            $currentJobRequests = $pendingJobRequestRepository->findBy(['job' => $pendingJobId]);
            $candidates = [];
            foreach ($currentJobRequests as $currentJobRequest) {
                $candidates[] = $currentJobRequest->getCandidate();
            }
            $currentJob = $jobRepository->find($pendingJobId);
            $requests[] = ['job' => $currentJob, 'candidates' => $candidates]; 
        }

        return $this->render('consultant/validPostulations.html.twig', [
            'user' => $user,
            'requests' => $requests
        ]);
    }


    // ********************** Actions **************************************************************************

    
    /**
     * @Route("/activate-recruiter/{id<\d+>}", name="activate_recruiter")
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
     * @Route("/activate-candidate/{id<\d+>}", name="activate_candidate")
     */
    public function activateCandidate($id, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {  
        // redirect to 404 if user not found
        if (!$userRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('Le candidat avec l\'id %s n\'existe pas', $id));
        }

        $candidate = $userRepository->find($id);
        $candidate->setIsActive(true);
        $entityManager->persist($candidate);
        $entityManager->flush();

        return $this->redirectToRoute('consultant_home');
    }

    /**
     * @Route("/valid-job/{id<\d+>}", name="valid_job")
     */
    public function validJob($id, JobRepository $jobRepository, EntityManagerInterface $entityManager)
    {
        if (!$jobRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('L\'annonce avec l\id %s n\'existe pas', $id));
        }

        $job = $jobRepository->find($id);
        $job->setIsValid(true);
        $entityManager->persist($job);
        $entityManager->flush();

        return $this->redirectToRoute('valid_jobs');
    }

    /**
     * @Route("/valid-job-request/{jobId<\d+>}/{candidateId<\d+>}", name="valid_job_request")
     */
    public function validJobRequest($jobId, $candidateId, PendingJobRequestRepository $pendingJobRequestRepository, EntityManagerInterface $entityManager, UserRepository $userRepository, JobRepository $jobRepository, MailerInterface $mailer)
    {
        if (!$userRepository->find($candidateId) || !$jobRepository->find($jobId)) {
            throw $this->createNotFoundException('L\'annonce ou le candidat renseign?? n\'existe pas');
        }

        $pendingJobRequest = $pendingJobRequestRepository->findOneBy([
            'job' => $jobId,
            'candidate' => $candidateId
        ]);
        
        if ($pendingJobRequest) {
            $entityManager->remove($pendingJobRequest);
            $entityManager->flush();
        }

        $candidate = $userRepository->find($candidateId);
        $job = $jobRepository->find($jobId);

        $validJobRequest = new ValidJobRequest();
        $validJobRequest->setCandidate($candidate);
        $validJobRequest->setJob($job);

        $entityManager->persist($validJobRequest);
        $entityManager->flush();

        // send email to recruiter
        $recruiterEmail = $userRepository->find($job->getRecruiter())->getEmail();

        $email = (new Email())
            ->from('trtConseil@gmail.com')
            ->to($recruiterEmail)
            ->attachFromPath('uploads/cv/' . $candidate->getCvFilename())
            ->subject('Un candidat vient de postuler ?? votre annonce')
            ->html('<p>Le candidat ' . $candidate->getFirstName() . ' ' . $candidate->getLastName() . ' vient de postuler ?? votre annonce "' . $job->getTitle() . '".</p>
                    <p>Vous trouverez son CV en pi??ce jointe.</p>
                    <br />
                    <p>Ceci est un mail automatique, merci de ne pas y r??pondre.</p>');
        
        $mailer->send($email);

        return $this->redirectToRoute('valid_postulations');
    }
}
