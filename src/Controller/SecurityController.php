<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Maraicher;
use App\Form\MaraicherType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
    ////////REGISTRATION//////////////
    #[Route(path: '/user/inscription', name: 'user_inscription')]
    public function inscriptionUser(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager): Response
    {
        //Création d'un nouvel objet User
        $user = new User();
        //Création du formulaire relié à l'entité User
        $formUser = $this->createForm(UserType::class, $user);
        //Analyse de la requête
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            //Hachage mot de passe
            $hash = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            //Enregistrement en BDD
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription a bien été pris en compte !');
            return $this->redirectToRoute('user_login');
        }

        return $this->render('security/user_inscription.html.twig', [
            'formUser' => $formUser->createView()
        ]);
    }

    #[Route(path: '/maraicher/inscription', name: 'maraicher_inscription')]
    public function inscriptionMaraicher(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager)
    {
        $maraicher = new Maraicher();
        $formMar = $this->createForm(MaraicherType::class, $maraicher);
        $formMar->handleRequest($request);

        if ($formMar->isSubmitted() && $formMar->isValid()) {

            //Récupere le logo de l'entreprise 
            $maraicherFile = $formMar->get('logo')->getData();

            //Si il y a un fichier
            if($maraicherFile)
            {
                //Génére un nouveau nom de fichier pour l'image de couverture
                $fichierLogo = md5(uniqid()) . '.' . $maraicherFile->guessExtension();
                //Envoie du fichier le folder
                $maraicherFile->move(
                    $this->getParameter('logo_directory'),
                    $fichierLogo
                );
                //Envoie le nom du fichier en BDD
                $maraicher->setLogo($fichierLogo);
            }

            //Hachage mot de passe
            $hash = $encoder->hashPassword($maraicher, $maraicher->getPassword());
            $maraicher->setPassword($hash);

            //Enregistrement en BDD
            $entityManager->persist($maraicher);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription a bien été pris en compte !');
            return $this->redirectToRoute('maraicher_login');
        }

        return $this->render('security/maraicher_inscription.html.twig', [
            'formMar' => $formMar->createView()
        ]);
    }



    //////////LOGIN/////////////
    #[Route(path: '/user/login', name: 'user_login')]
    public function loginUser(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        //Obtient un erreur si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/user_connexion.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/maraicher/login', name: 'maraicher_login')]
    public function loginMaraicher(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/maraicher_connexion.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    ////////LOGOUT////////////
    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout()
    {
        return $this->redirectToRoute('index');
    }
}
