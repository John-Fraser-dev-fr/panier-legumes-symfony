<?php

namespace App\Controller;

use App\Entity\Legume;
use App\Repository\LegumeRepository;
use App\Repository\MaraicherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{

    #[Route('/user/panier', name: 'index_panier')]
    public function index(SessionInterface $session, LegumeRepository $repoLegume, MaraicherRepository $maraicherRepository): Response
    {
        //Récupére le panier
        $panier = $session->get("panier", []);

        //Création des données du panier
        $dataPanier = [];
        $total = 0;
        $maraicher = null;


        

        //Boucle sur panier pour extraire la key(id) associé a la quantité
        foreach($panier as $id => $quantite)
        {
            $legume = $repoLegume->find($id);
            $maraicher = $legume->getMaraicher();
            $dataPanier[] = [
                "legume" => $legume,
                "quantite" => $quantite,
            ];
            
            $total += $legume->getPrix() * $quantite;
        }

      

        return $this->render('panier/index.html.twig', [
            "dataPanier" => $dataPanier,
            "total" => $total,
            "maraicher" => $maraicher
        ]);
    }


    #[Route('/panier/add/{id}', name: 'add_panier')]
    public function add(SessionInterface $session, Legume $legume): Response
    {
        //Récupére le panier, si pas de panier : renvoie un tableau vide
        $panier = $session->get("panier", []);
        //récupére l'id du légume en question
        $id = $legume->getId();

        //Si le panier n'est pas vide
        if(!empty($panier[$id]))
        {
            //incrémentation
            $panier[$id]++;
        }
        else
        {
            //sinon initialisation à 1
            $panier[$id] = 1;
        }

        //Sauvegarde le panier dans la session
        $session->set("panier", $panier);


        return $this->redirectToRoute('index_panier');
    }


    #[Route('/panier/remove/{id}', name: 'remove_panier')]
    public function remove(SessionInterface $session, Legume $legume): Response
    {
        //Récupére le panier
        $panier = $session->get("panier", []);
        //récupére l'id du légume en question
        $id = $legume->getId();

        //Si le panier existe
        if(!empty($panier[$id]))
        {
            if($panier[$id] > 1)
            {
                $panier[$id]--;
            }
            else
            {
                unset($panier[$id]);
            }  
        }
       
        //Sauvegarde le panier dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute('index_panier');
    }


    #[Route('/panier/delete/{id}', name: 'delete_panier')]
    public function deleteLegume(SessionInterface $session, Legume $legume): Response
    {
        //Récupére le panier
        $panier = $session->get("panier", []);
        //récupére l'id du légume en question
        $id = $legume->getId();

        //Si le panier n'existe pas
        if(!empty($panier[$id]))
        {
            unset($panier[$id]); 
        }
     
        //Sauvegarde le panier dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute('index_panier');
    }


}
