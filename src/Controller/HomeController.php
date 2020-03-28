<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\SearchType;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homeDisplay(Request $request, AnnoncesRepository $annoncesRepo): Response 
    {
        $annonces = $this->getDoctrine()->getRepository(Annonces::class)->findBy([], ['id' => 'desc'], 5);
        
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        $resultSearchData = '';  // On initialise la variable

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();
            $resultSearchData = $annoncesRepo->searchAnnonces($searchData);
        }
        
        return $this->render('home/index.html.twig', [
            'title_home' => "Bienvenue sur Proxi'Car, le site d'annonces pour la vente de véhicules d'occasions",
            'annonces' => $annonces,
            'searchForm' => $searchForm->createView(),
            'searchResult' => $resultSearchData 
        ]);
    }
}