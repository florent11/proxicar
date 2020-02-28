<?php

namespace App\Controller;

use App\Entity\Annonces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $annonces = $this->getDoctrine()->getRepository(Annonces::class)->findBy([], ['id' => 'desc'], 5);
        
        return $this->render('home/index.html.twig', [
            'title_home' => "Bienvenue sur Proxi'Car, le site d'annonces pour la vente de véhicules d'occasions",
            'annonces' => $annonces 
        ]);
    }
}
