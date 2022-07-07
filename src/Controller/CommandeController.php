<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\LegumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/user/commande', name: 'index_commande')]
    public function index(SessionInterface $session, LegumeRepository $repoLegume, EntityManagerInterface $entityManager): Response
    {
        //Récupére le panier
        $panier = $session->get("panier");

        $total = 0;


        //Boucle sur panier pour extraire la key(id) associé a la quantité
        foreach($panier as $id => $quantite)
        {
            $legume = $repoLegume->find($id);
            $maraicher = $legume->getMaraicher();
            $dataPanier[] = [
                 "legume" => $legume,
                 "quantite" => $quantite,
                 "maraicher" => $maraicher
            ];
            $total += $legume->getPrix() * $quantite;
        }

        $commande = new Commande;

        $commande->setMontant($total)
                ->setUser($this->getUser())
                ->setDate(new \DateTime())
                ->setStatus(0);

        //Enregistrement en BDD
        $entityManager->persist($commande);
        $entityManager->flush();

       

        return $this->render('commande/index.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total
        ]);
    }
}
