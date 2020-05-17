<?php

namespace App\Controller;

use App\Entity\Annonces;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    /**
    * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
    */
    public function index(Request $request)
    {
        // Nous récupérons le nom d'hôte depuis l'URL
        $hostname = $request->getSchemeAndHttpHost();

        // On initialise un tableau pour lister les URLs
        $urls = [];

        // On ajoute les URLs "statiques"
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('annonces')];

        // On ajoute les URLs dynamiques des articles dans le tableau
        foreach ($this->getDoctrine()->getRepository(Annonces::class)->findAll() as $annonce) {
            $annDate = $annonce->getAnnDate();
            
            foreach ($annonce->getImages() as $annImage) {
                $images = [
                    'loc' => '/uploads/images/featured/'.$annImage->getImageName(), // URL de l'image
                    'title' => $annonce->getAnnTitre()  // Optionel, texte qui décrit l'image
                ];

                $urls[] = [
                    'loc' => $this->generateUrl('annonce', [
                        'slug' => $annonce->getSlug(),
                    ]),
                    'image' => $images
                ]; 
            }
        }

        // Fabrication de la réponse XML
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname]
            )
        );

        // Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;
    }
}
