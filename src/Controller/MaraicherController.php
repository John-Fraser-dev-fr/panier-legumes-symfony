<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MaraicherController extends AbstractController
{
   

    #[Route(path: '/maraicher/index', name: 'index_maraicher')]
    public function index()
    {
        return $this->render('maraicher/index.html.twig');
    }
}

