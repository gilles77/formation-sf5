<?php

namespace App\Controller;

use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
      return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheck(): Response
    {
        // ne returne jamais de response. C'est juste pour le composant security qu'elle est déclarée

        throw new \InvalidArgumentException('this action is managed by ');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response
    {
        throw new \InvalidArgumentException('this action is managed by ');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
      //$this->createForm('App\\Form\\RegisterType');
      $form = $this->createForm(RegisterType::class);

      // Bind les data qui se trouvent dans la request vers l'objet User
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid() && $form->get('terms')->getData()){
          $user = $form->getData();

          $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());

          $user->setPassword($hashedPassword);
          $entityManager->persist($user);
          $entityManager->flush();
          dump($user);
      }

      return $this->render('security/register.html.twig', [
        'register_form' => $form->createView()
      ]);
    }
}
