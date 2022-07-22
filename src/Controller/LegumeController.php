<?php

namespace App\Controller;

use App\Repository\LegumeRepository;
use Doctrine\ORM\EntityManagerInterface;
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


    #[Route('/maraicher/index/supp/{id}', name: 'delete_legume')]
    public function delete(LegumeRepository $legumeRepository, $id, EntityManagerInterface $entityManager)
    {
        //Récupére le légume
        $legume = $legumeRepository->find($id);

        //Récupére l'image du légume
        $imageLegume = $legume->getImage();
        //Récupére le chemin 
        $cheminImageLegume = $this->getParameter('image_legume_directory') . '/' . $imageLegume;

        //Si il existe, on supprime du fichier
        if (file_exists($cheminImageLegume))
        {
            unlink($cheminImageLegume);
        }

        //Supprimer le légume de la BDD
        $entityManager->remove($legume);
        $entityManager->flush();

        $this->addFlash('danger', 'Votre légume a bien été supprimé !');
        return $this->redirectToRoute('index_maraicher');

      
    }
}
