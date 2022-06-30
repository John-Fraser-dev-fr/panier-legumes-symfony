<?php

namespace App\Controller;

use App\Repository\LegumeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegumeController extends AbstractController
{
    #[Route('/legumes', name: 'legumes')]
    public function showAll(LegumeRepository $legumeRepository): Response
    {
        $legumes = $legumeRepository->findAll();

        return $this->render('legume/index.html.twig', [
            'legumes' => $legumes,
        ]);
    }
}
