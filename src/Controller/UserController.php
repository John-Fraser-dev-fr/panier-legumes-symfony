<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{
    #[Route(path: '/user/index', name: 'index_user')]
    public function index()
    {
        return $this->render('user/index.html.twig');
    }
}
