<?php

namespace App\Controller;

use App\Form\SelectDptType;
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
    public function choiceDpt(Request $request, SessionInterface $session)
    {
        //Récupére le panier, si pas de panier : renvoie un tableau vide
        $panier = $session->get('panier');

        $formSelectDpt = $this->createForm(SelectDptType::class, null);
        $formSelectDpt->handleRequest($request);

        if ($formSelectDpt->isSubmitted())
        {
            //Récupere le num du département
            $n_dpt = $formSelectDpt->get('choix_dpt')->getData();

            if($n_dpt && $panier == [])
            {
                return $this->redirectToRoute('home_choice_dpt_id', ['n_dpt' => $n_dpt]);
            }
            else 
            {
                $this->addFlash('danger', 'Un panier est déjà en cours !');
                return $this->redirectToRoute('home_choice_dpt');
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
