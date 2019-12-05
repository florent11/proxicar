<?php

namespace App\Controller;

use App\Entity\Annonces;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    /**
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
}
