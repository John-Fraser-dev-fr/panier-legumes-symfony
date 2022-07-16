<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\DateOrderType;
use App\Entity\DetailsCommande;
use App\Repository\LegumeRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DetailsCommandeRepository;
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
        //Récupére le panier en session
        $panier = $session->get("panier");

        //Récupére la date en session
        $date_order = $session->get("date", []);

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
                ->setDate($date_order)
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

    #[Route('/user/commande/date', name: 'commande_date')]
    public function dateForCommande(Request $request, SessionInterface $session, LegumeRepository $repoLegume): Response
    {
        //Récupére la date en session, si vide, il le créer en renvoyant un tableau vide
        $session->get("date", []);

        $date_now = new \DateTime();
        
        //Formulaire choix date
        $formDateCommande = $this->createForm(DateOrderType::class, null);
        $formDateCommande->handleRequest($request);

        if ($formDateCommande->isSubmitted())
        {
            //Récupere la date sélectionner
            $date_order = $formDateCommande->get('date')->getData();

            //Si la date de commande est inferieur ou  égale à la date d'aujourd'hui
            if($date_order <= $date_now)
            {
                $this->addFlash('danger', 'Vous ne pouvez pas choisir une date antérieur à aujourd\'hui !');
                return $this->redirectToRoute('commande_date');
            }
            else
            {
                //Sauvegarde le panier
                $session->set("date", $date_order);

                return $this->redirectToRoute('index_commande');
            }

        }

        return $this->render('panier/date.html.twig', [
              "formDateCommande" => $formDateCommande->createView()
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
