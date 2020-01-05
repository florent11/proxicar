<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\ModifAnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AnnonceController extends AbstractController
{
    /**
     * Affichage de toutes les annonces
     * 
     * @Route("/annonces", name="annonces")
     */
    public function listAnnonces()
    {
        $annonces = $this->getDoctrine()->getRepository(Annonces::class)->findAll();
        //dd($annonces);
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * Affichage d'une annonce précise
     * 
     * @Route("/annonce/{slug}", name="annonce")
     */
    public function displayAnnonce($slug)
    {
        $annonce = $this->getDoctrine()->getRepository(Annonces::class)->findOneBy(['slug' => $slug]);
       // dd($annonces);
        return $this->render('annonce/annonce_article.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * Formulaire de modification d'une annonce
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/modif-annonce/{id}", name="modif_annonce")
     */
    public function modifAnnonce(Request $request, $id)
    {
        $annonce = $this->getDoctrine()->getRepository(Annonces::class)->find($id);

        $form = $this->createForm(ModifAnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Les modifications ont été enregistrées avec succès !'
            );
        }
        return $this->render('annonce/modif_annonce.html.twig', [
            'modifAnnonceForm' => $form->createView(),
        ]);
    }
}

