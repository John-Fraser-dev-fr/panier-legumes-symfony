<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\DetailsCommandeRepository;
use App\Repository\LegumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/user/commande', name: 'index_commande')]
    public function index(SessionInterface $session, LegumeRepository $repoLegume, EntityManagerInterface $entityManager, Request $request): Response
    {
        //Récupére le panier
        $panier = $session->get("panier");
        //Initialise le total
        $total = 0;

        //Nouvelle commande
        $commande = new Commande();
        //Formulaire commande 
        $formCommande = $this->createForm(CommandeType::class, $commande);

        //Analyse de la requête
        $formCommande->handleRequest($request);
        if ($formCommande->isSubmitted() && $formCommande->isValid()) {

            //Boucle sur panier pour extraire la key(id) associé a la quantité
            foreach ($panier as $id => $quantite) 
            {
                $legume = $repoLegume->find($id);
                $maraicher = $legume->getMaraicher();
                $dataPanier[] = [
                    "legume" => $legume,
                    "quantite" => $quantite,
                    "maraicher" => $maraicher
                ];
                $total += $legume->getPrix() * $quantite;

                //Nouveau detail commande
                $detailsCommande = new DetailsCommande();
  
                //Ajout de details commande lié à la commande
                $commande->addDetailsCommande(
                    $detailsCommande->setCommande($commande->getId()),
                    $detailsCommande->setQuantite($quantite),
                    $detailsCommande->setPrix($legume->getPrix() * $quantite),
                    $detailsCommande->setLegume($legume)
                );
            }

            //Ajout de la commande
            $commande->setMontant($total)
                ->setUser($this->getUser())
                ->setDate(new \DateTime())
                ->setStatus(0)
            ;
            //Enregistrement en BDD
            $entityManager->persist($commande);
            $entityManager->flush();

            //Vide le panier en session
            $session->set("panier", []);

            $this->addFlash('success', 'Votre commande a bien été prise en compte !');
            return $this->redirectToRoute('index');
        }

        return $this->render('commande/index.html.twig', [
            'total' => $total,
            'formCommande' => $formCommande->createView()
        ]);
    }

    #[Route('/user/mes_commandes', name: 'commandeByUser')]
    public function showByUser(CommandeRepository $commandeRepository): Response
    {
        //récupére l'utilisateur
        $user = $this->getUser();

        $commandes = $commandeRepository->findBy(['user' => $user], ['id' => 'desc']);

        return $this->render('user/commandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/user/commande/{id}', name: 'info_commande')]
    public function infoCommande(CommandeRepository $commandeRepository, $id, DetailsCommandeRepository $detailsCommandeRepository): Response
    {
        //récupére l'utilisateur
        $user = $this->getUser();
        //récupére la commande
        $commande = $commandeRepository->find($id);
        //récupére le propiétaire de la commande
        $commandeOfUser = $commande->getUser();

        //récupére le details commande 
        $commandeDetails = $detailsCommandeRepository->findBy(['commande' => $commande]);

        return $this->render('commande/info.html.twig', [
            'commande' => $commande,
            'commandeDetails' => $commandeDetails
        ]);
    }


    #[Route('/user/commande/{id}/supp', name: 'delete_commande')]
    public function delete(CommandeRepository $commandeRepository, $id, EntityManagerInterface $entityManager)
    {
        //récupére l'utilisateur
        $user = $this->getUser();
        //récupére la commande
        $commande = $commandeRepository->find($id);
        //récupére le propiétaire de la commande
        $commandeOfUser = $commande->getUser();

        //Si le propriétaire de la commande est égale à l'utilisateur
        if($commandeOfUser === $user)
        {
            //Supprime de la BDD
            $entityManager->remove($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a bien été supprimée !');
            return $this->redirectToRoute('commandeByUser');
        }
        else
        {
            $this->addFlash('danger', 'Vous n\'avez pas le droit de supprimer cette commande !');
            return $this->redirectToRoute('index');
        }
   
    }
}
