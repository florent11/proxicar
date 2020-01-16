<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\CreerAnnonceType;
use App\Form\ModifAnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        //dd($annonce);
        return $this->render('annonce/annonce_article.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * Affichage du panneau de Gestion des annonces publiées
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/gestion-annonces", name="gestion_annonces")
     */
    public function displayAnnManager()
    {
        $userAnnonces = $this->getUser()->getAnnonces();
        dd($userAnnonces);
        return $this->render('account/gestion_annonces.html.twig', [
            'controller_name' => 'Gestion Des Annonces'
        ]);
    }

    /**
     * Formulaire de création d'une annonce
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/creer-annonce", name="creer_annonce")
     */
    public function creerAnnonce(Request $request): Response
    {
        $annonce = new Annonces;

        $form = $this->createForm(CreerAnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setAnnDate(new \DateTime('now'));
            $annonce->setUsers($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'L\'annonce a été soumise au modérateur pour validation.'
            );

            return $this->redirectToRoute('user_panel');
        }
        return $this->render('annonce/creer_annonce.html.twig', [
            'creerAnnonceForm' => $form->createView(),
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
                'success',
                'Les modifications ont été enregistrées avec succès !'
            );

            return $this->redirectToRoute('user_panel');
        }
        return $this->render('annonce/modif_annonce.html.twig', [
            'modifAnnonceForm' => $form->createView(),
        ]);
    }
}

