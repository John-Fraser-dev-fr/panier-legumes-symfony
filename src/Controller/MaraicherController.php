<?php

namespace App\Controller;

use App\Entity\Maraicher;
use App\Form\MaraicherType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MaraicherController extends AbstractController
{
    #[Route(path: '/inscription/maraicher', name: 'maraicher_inscription')]
    public function inscription(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager)
    {
        $maraicher = new Maraicher();

        $formMar = $this->createForm(MaraicherType::class, $maraicher);

        $formMar->handleRequest($request);

        if ($formMar->isSubmitted() && $formMar->isValid()) {


            //Récupere le logo de l'entreprise 
            $annonceFile = $formMar->get('logo')->getData();

            //Si il y a un fichier
            if($annonceFile)
            {
                //Génére un nouveau nom de fichier pour l'image de couverture
                $fichierLogo = md5(uniqid()) . '.' . $annonceFile->guessExtension();

                //Envoie du fichier le folder
                $annonceFile->move(
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
            return $this->redirectToRoute('index');
        }

        return $this->render('maraicher/inscription.html.twig', [
            'formMar' => $formMar->createView()
        ]);
    }

    #[Route(path: '/maraicher/login', name: 'maraicher_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('maraicher/connexion.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout()
    {
        return $this->redirectToRoute('index');
    }

    #[Route(path: '/maraicher/index', name: 'index_maraicher')]
    public function index()
    {
        return $this->render('maraicher/index.html.twig');
    }
}

