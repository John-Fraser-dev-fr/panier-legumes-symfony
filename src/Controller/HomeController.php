<?php

namespace App\Controller;

use App\Repository\LegumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/index', name: 'index')]
    public function showAll(LegumeRepository $legumeRepository): Response
    {
        $legumes = $legumeRepository->findAll();

        return $this->render('home/index.html.twig', [
            'legumes' => $legumes,
        ]);
    }

    
}
