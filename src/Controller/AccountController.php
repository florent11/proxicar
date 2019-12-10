<?php

namespace App\Controller;

use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * Formulaire de Modification du profil utilisateur
     * 
     * @Route("/modif-profil", name="edit_user_profil")
     */
    public function editUserProfil(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Les modifications ont été enregistrées avec succès !'
            );
        }

        return $this->render('account/modif_profil.html.twig', [
            'modifUserProfilForm' => $form->createView(),
        ]);
    }


}