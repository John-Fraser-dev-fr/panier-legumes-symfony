<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{

    #[Route(path:'/registration', name: 'registration')]
    public function inscription(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager): Response
    {
        //Création d'un nouvel objet User
        $user = new User();
        //Création du formulaire relié à l'entité User
        $formUser = $this->createForm(UserType::class, $user);

        //Analyse de la requête
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid())
        {
            //Hachage mot de passe
            $hash = $encoder->hashPassword($user,$user->getPassword());
            $user->setPassword($hash);

            //Enregistrement en BDD
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription a bien été pris en compte !');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/registration.html.twig', [
            'formUser'=> $formUser->createView()
        ]);
    }

    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        //Obtient un erreur si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout()
    {
        return $this->redirectToRoute('app_home');
    }
}
