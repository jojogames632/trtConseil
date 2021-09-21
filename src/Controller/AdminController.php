<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\PendingJobRequestRepository;
use App\Repository\UserRepository;
use App\Repository\ValidJobRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="admin_home")
     */
    public function manageDatabase(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $candidates = $userRepository->findAllUsersByRole('ROLE_CANDIDATE');
        $recruiters = $userRepository->findAllUsersByRole('ROLE_RECRUITER');
        $consultants = $userRepository->findAllUsersByRole('ROLE_CONSULTANT');
        $admins = $userRepository->findAllUsersByRole('ROLE_ADMIN');

        return $this->render('admin/manageDatabase.html.twig', [
            'user' => $user,
            'candidates' => $candidates,
            'recruiters' => $recruiters,
            'consultants' => $consultants,
            'admins' => $admins
        ]);
    }

    /**
     * @Route("/create-consultant", name="create_consultant")
     */
    public function createConsultant(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $newUser = new User();
        $form = $this->createForm(RegistrationFormType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser->setPassword(
                $passwordHasher->hashPassword(
                    $newUser,
                    $form->get('plainPassword')->getData()
                )
            );
            $newUser->setIsActive(true);

            $newUser->setRoles(["ROLE_CONSULTANT"]);

            $entityManager->persist($newUser);
            $entityManager->flush();

            $this->addFlash('success', 'Compte consultant créé avec succès !');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/createConsultant.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-candidate/{id<\d+>}", name="delete_candidate")
     */
    public function deleteCandidate($id, UserRepository $userRepository, EntityManagerInterface $entityManager, PendingJobRequestRepository $pendingJobRequestRepository, ValidJobRequestRepository $validJobRequestRepository)
    {
        if (!$userRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('Le candidat avec l\id numéro %s n\'existe pas', $id));
        }

        // delete all data about candidate before delete him
        $pendingJobRequests = $pendingJobRequestRepository->findBy(['candidate' => $id]);
        $validJobRequests = $validJobRequestRepository->findBy(['candidate' => $id]);
        if (!empty($pendingJobRequests)) {
            foreach ($pendingJobRequests as $pendingJobRequest) {
                $entityManager->remove($pendingJobRequest->getId());
                $entityManager->flush();
            }
        }
        if (!empty($validJobRequests)) {
            foreach ($validJobRequests as $validJobRequest) {
                $entityManager->remove($validJobRequest->getId());
                $entityManager->flush();
            }
        }
        
        $candidate = $userRepository->find($id);
        $entityManager->remove($candidate);
        $entityManager->flush();

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/delete-recruiter/{id<\d+>}", name="delete_recruiter")
     */
    public function deleteRecruiter($id, UserRepository $userRepository, EntityManagerInterface $entityManager, PendingJobRequestRepository $pendingJobRequestRepository, ValidJobRequestRepository $validJobRequestRepository)
    {
        if (!$userRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('Le recruteur avec l\id numéro %s n\'existe pas', $id));
        }

        // delete all data about recruiter before delete him
        $pendingJobRequests = $pendingJobRequestRepository->findBy(['recruiter' => $id]);
        $validJobRequests = $validJobRequestRepository->findBy(['recruiter' => $id]);
        if (!empty($pendingJobRequests)) {
            foreach ($pendingJobRequests as $pendingJobRequest) {
                $entityManager->remove($pendingJobRequest->getId());
                $entityManager->flush();
            }
        }
        if (!empty($validJobRequests)) {
            foreach ($validJobRequests as $validJobRequest) {
                $entityManager->remove($validJobRequest->getId());
                $entityManager->flush();
            }
        }

        $recruiter = $userRepository->find($id);
        $entityManager->remove($recruiter);
        $entityManager->flush();

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/delete-consultant/{id<\d+>}", name="delete_consultant")
     */
    public function deleteConsultant($id, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        if (!$userRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('Le consultant avec l\id numéro %s n\'existe pas', $id));
        }

        $consultant = $userRepository->find($id);
        $entityManager->remove($consultant);
        $entityManager->flush();

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/delete-admin/{id<\d+>}", name="delete_admin")
     */
    public function deleteAdmin($id, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        if (!$userRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('L\'admin avec l\id numéro %s n\'existe pas', $id));
        }

        $admin = $userRepository->find($id);
        $entityManager->remove($admin);
        $entityManager->flush();

        return $this->redirectToRoute('admin_home');
    }
}
