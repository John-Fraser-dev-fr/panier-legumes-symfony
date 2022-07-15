<?php

namespace App\Controller;

use App\Form\SelectDptType;
use App\Repository\LegumeRepository;
use App\Repository\MaraicherRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorExists;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/user/choice/dpt', name: 'home_choice_dpt')]
    public function choiceDpt(Request $request, SessionInterface $session, LegumeRepository $legumeRepository)
    {
        //Récupére le panier en cours sinon renvoie un tableau vide
        $panier = $session->get('panier', []);

        //Boucle sur panier pour extraire la key(id) => value(quantité)
        foreach ($panier as $id => $quantite)
        {
            //récupere le(s) légume(s) du panier
            $legume = $legumeRepository->find($id);
            //Récupere le maraicher associé
            $maraicher = $legume->getMaraicher();
            //Récupére le num de département associé au maraicher
            $nDpt =  $maraicher->getNDpt();
        }
        
        //Formulaire liste déroulante des départements
        $formSelectDpt = $this->createForm(SelectDptType::class, null);
        $formSelectDpt->handleRequest($request);

        //Si il est soumis
        if ($formSelectDpt->isSubmitted())
        {
            //Récupere le num du département choisi dans la liste
            $n_dpt = $formSelectDpt->get('choix_dpt')->getData();

            //Si le panier n'est pas vide
            //ET que le num dpt choisi est différent du num dpt du maraicher
            if($panier != [] && $n_dpt != $nDpt)
            {
                $this->addFlash('danger', 'Vous ne pouvez choisir qu\'un seul département par panier !');
                return $this->redirectToRoute('home_choice_dpt');
            }
            if($n_dpt)
            {
                return $this->redirectToRoute('home_choice_dpt_id', ['n_dpt' => $n_dpt]);
            }
                   
        }

        return $this->render('home/choix_dpt.html.twig', [
            'formSelectDpt' => $formSelectDpt->createView()
        ]);
    }


    #[Route('/user/choice/dpt/{n_dpt}', name: 'home_choice_dpt_id')]
    public function validateDpt(MaraicherRepository $maraicherRepository, $n_dpt): Response
    {
        $maraichersByDpt = $maraicherRepository->findBy(['n_dpt' => $n_dpt]);

        return $this->render('maraicher/showByDpt.html.twig', [
            "maraichersByDpt" => $maraichersByDpt
        ]);
    }
}
