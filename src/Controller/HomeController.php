<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\AdType;
use App\Repository\JobRepository;
use App\Repository\UserRepository;
use App\Repository\ValidJobRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @Route("/recruiter/home", name="recruiter_home")
     * 
     * @IsGranted("ROLE_RECRUITER")
     */
    public function recruiterIndex(ValidJobRequestRepository $validJobRequestRepository, JobRepository $jobRepository, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
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

        return $this->render('home/recruiterIndex.html.twig', [
            'user' => $user,
            'validJobRequests' => $validJobRequests,
            'form' => $form->createView(),
            'errors' => $errors
        ]);
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
