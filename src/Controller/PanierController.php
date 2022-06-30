<?php

namespace App\Controller;

use App\Entity\Legume;
use App\Repository\LegumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'index_panier')]
    public function index(SessionInterface $session, LegumeRepository $repoLegume): Response
    {
        //Récupére le panier
        $panier = $session->get("panier", []);

        //Création des données du panier
        $dataPanier = [];
        $total = 0;

        //Boucle sur panier
        foreach($panier as $id => $quantite)
        {
            $legume = $repoLegume->find($id);
            $dataPanier[] = [
                "legume" => $legume,
                "quantite" => $quantite
            ];
            $total += $legume->getPrix() * $quantite;
        }


        return $this->render('panier/index.html.twig', [
            "dataPanier" => $dataPanier,
            "total" => $total        
        ]);
    }


    #[Route('/panier/add/{id}', name: 'add_panier')]
    public function add(SessionInterface $session, Legume $legume): Response
    {
        //Récupére le panier
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
            //sinon on le créer et on initialisation à 1
            $panier[$id] = array();
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
    public function delete(SessionInterface $session, Legume $legume): Response
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
