<?php

namespace App\Controller;

use App\Entity\Legume;
use App\Form\LegumeType;
use App\Form\StatusType;
use App\Form\StatutType;
use App\Repository\LegumeRepository;
use App\Repository\CommandeRepository;
use App\Repository\MaraicherRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DetailsCommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MaraicherController extends AbstractController
{



    #[Route(path: '/maraicher/index', name: 'index_maraicher')]
    public function index(MaraicherRepository $MaraicherRepository, LegumeRepository $legumeRepository, Request $request, EntityManagerInterface $entityManager, CommandeRepository $commandeRepository, FormFactoryInterface $formFactory)
    {
        //Récupére le maraicher connecté
        $user = $this->getUser();
        $maraicher = $MaraicherRepository->find(['id' => $user]);

        //récupére les commandes
        $commandes = $commandeRepository->findBy(['maraicher' => $maraicher]);

        //récupére les légumes associé au maraicher
        $legumes = $legumeRepository->findBy(['maraicher' => $maraicher]);

        //Création du légume
        $legume = new Legume();
        //Formulaire 
        $formLegume = $this->createForm(LegumeType::class, $legume);

        //Analyse de la requete
        $formLegume->handleRequest($request);
        if ($formLegume->isSubmitted() && $formLegume->isValid()) {
            //récupére l'image du légume transmise
            $legumeFile = $formLegume->get('image')->getData();

            //Si il y a un fichier
            if ($legumeFile) {
                //Génére un nouveau nom de fichier pour l'image de couverture
                $legumeImage = md5(uniqid()) . '.' . $legumeFile->guessExtension();
                //Envoie du fichier le folder
                $legumeFile->move(
                    $this->getParameter('image_legume_directory'),
                    $legumeImage
                );
                //Envoie le nom du fichier en BDD
                $legume->setImage($legumeImage);
            } else {
                $this->addFlash('danger', 'Une erreur s\'est produite au moment du téléchargement de l\'image !');
                return $this->redirectToRoute('index_maraicher');
            }

            $legume->setMaraicher($maraicher);

            //Enregistrement en BDD
            $entityManager->persist($legume);
            $entityManager->flush();

            $this->addFlash('success', 'Votre légume a bien été ajouté !');
            return $this->redirectToRoute('index_maraicher');
        }





  
        $forms = [];
      

        foreach ($commandes as $commande)
        {
            //Formulaire
            $form = $formFactory->createNamed(''.$commande->getId(), StatutType::class, $commande);
            $forms[$commande->getId()] = $form->createView();
            
           
        

      
            $form->handleRequest($request);

            
            if ($form->isSubmitted()) 
            {
                //récupére le status de la commande
                $statusCommande = $form->get('status')->getData();
    
                $commande->setStatus($statusCommande);
    
                //Enregistrement en BDD
                $entityManager->persist($commande);
                $entityManager->flush();
            } 
        
        }  

        

       
        //dd($forms);

        return $this->render('maraicher/index.html.twig', [
            'legumes' => $legumes,
            'commandes' => $commandes,
            'maraicher' => $maraicher,
            'formLegume' => $formLegume->createView(),
            'forms' => $forms
        ]);
    }










    #[Route(path: '/user/maraicher/{id}', name: 'maraicher')]
    public function show(MaraicherRepository $MaraicherRepository, $id, LegumeRepository $legumeRepository, SessionInterface $session)
    {
        //récupére le maraicher
        $maraicher = $MaraicherRepository->find($id);
        //récupére les légumes associé
        $legumes = $legumeRepository->findBy(['maraicher' => $maraicher]);

        //Récupére le panier en cours sinon renvoie un tableau vide
        $panier = $session->get('panier', []);

        if ($panier != []) {
            //Boucle sur panier pour extraire la key(id) => value(quantité)
            foreach ($panier as $id => $quantite) {
                //récupere le(s) légume(s) du panier
                $legume = $legumeRepository->find($id);
                //Récupere le maraicher associé
                $maraicherPanier = $legume->getMaraicher();
                //Récupére le num dpt du maraicher
                $n_dpt = $maraicherPanier->getNDpt();
            }

            if ($maraicher != $maraicherPanier) {
                $this->addFlash('danger', 'Vous ne pouvez choisir qu\'un seul maraîcher par panier !');
                return $this->redirectToRoute('home_choice_dpt_id', ['n_dpt' => $n_dpt]);
            }
        }

        return $this->render('maraicher/show.html.twig', [
            'maraicher' => $maraicher,
            'legumes' => $legumes
        ]);
    }
}
