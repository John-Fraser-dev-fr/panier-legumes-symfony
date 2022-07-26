<?php

namespace App\Controller;

use App\Form\LegumeType;
use App\Repository\LegumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;

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


    #[Route('/maraicher/index/edit/{id}', name: 'edit_legume')]
    public function edit(LegumeRepository $legumeRepository, $id, EntityManagerInterface $entityManager, Request $request)
    {
        //Récupére le légume
        $legume = $legumeRepository->find($id);

        //Formulaire 
        $formLegume = $this->createForm(LegumeType::class, $legume);

        //Analyse de la requete
        $formLegume->handleRequest($request);
        if($formLegume->isSubmitted() && $formLegume->isValid())
        {
            //récupére l'image du légume transmise
            $legumeFile = $formLegume->get('image')->getData();

            //Si il y a un fichier
            if($legumeFile)
            {
                //Génére un nouveau nom de fichier pour l'image de couverture
                $legumeImage = md5(uniqid()) . '.' . $legumeFile->guessExtension();
                //Envoie du fichier le folder
                $legumeFile->move(
                    $this->getParameter('image_legume_directory'),
                    $legumeImage
                );
                //Envoie le nom du fichier en BDD
                $legume->setImage($legumeImage);               
            }
           

            //Enregistrement en BDD
            $entityManager->persist($legume);
            $entityManager->flush();

            $this->addFlash('success', 'Votre légume a bien été modifié !');
            return $this->redirectToRoute('index_maraicher');
        }

        return $this->render('maraicher/edit.html.twig',[
            'legume' => $legume,
            'formLegume' => $formLegume->createView()
        ]);

      
    }

   
}
