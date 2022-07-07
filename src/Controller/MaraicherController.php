<?php

namespace App\Controller;

use App\Repository\LegumeRepository;
use App\Repository\MaraicherRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MaraicherController extends AbstractController
{
    #[Route(path: '/maraicher/index', name: 'index_maraicher')]
    public function index()
    {
        return $this->render('maraicher/index.html.twig');
    }

    #[Route(path: '/user/maraicher/all', name: 'all_maraicher')]
    public function showAll(MaraicherRepository $MaraicherRepository)
    {
        $maraichers= $MaraicherRepository->findAll();

        return $this->render('maraicher/showAll.html.twig', [
            'maraichers' => $maraichers
        ]);
    }

    #[Route(path: '/user/maraicher/{id}', name: 'maraicher')]
    public function show(MaraicherRepository $MaraicherRepository, $id, LegumeRepository $legumeRepository)
    {
        $maraicher= $MaraicherRepository->find($id);

        $legumes = $legumeRepository->findBy(['maraicher' => $maraicher]);

        return $this->render('maraicher/show.html.twig', [
            'maraicher' => $maraicher,
            'legumes' => $legumes
        ]);
    }
}

