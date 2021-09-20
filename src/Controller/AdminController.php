<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="admin_home")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
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
}
