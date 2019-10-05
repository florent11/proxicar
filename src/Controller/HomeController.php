<?php

namespace App\Controller;

//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\HomeController;

class HomeController extends Controller
{
    /**
     *@Route("/", name="homepage")
     */
    public function home()
    {
        return $this->render(
            'home.html.twig',
            [ 'title' => "Bienvenue sur le site" ]
        );
    }
}

?>