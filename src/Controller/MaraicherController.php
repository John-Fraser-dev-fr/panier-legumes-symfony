<?php

namespace App\Controller;

use App\Repository\LegumeRepository;
use App\Repository\MaraicherRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class MaraicherController extends AbstractController
{
    #[Route(path: '/maraicher/index', name: 'index_maraicher')]
    public function index()
    {
        return $this->render('maraicher/index.html.twig');
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

        if($panier != [])
        {
            //Boucle sur panier pour extraire la key(id) => value(quantité)
            foreach ($panier as $id => $quantite)
            {
                //récupere le(s) légume(s) du panier
                $legume = $legumeRepository->find($id);
                //Récupere le maraicher associé
                $maraicherPanier = $legume->getMaraicher(); 
                //Récupére le num dpt du maraicher
                $n_dpt = $maraicherPanier->getNDpt();  
            }

            if($maraicher != $maraicherPanier)
            {
                $this->addFlash('danger', 'Vous ne pouvez choisir qu\'un seul maraîcher par panier !');
                return $this->redirectToRoute('home_choice_dpt_id', ['n_dpt'=> $n_dpt]);
            }
        }
        
        return $this->render('maraicher/show.html.twig', [
            'maraicher' => $maraicher,
            'legumes' => $legumes
        ]);
    }
}

