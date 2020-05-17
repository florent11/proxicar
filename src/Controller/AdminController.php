<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Annonces;
use App\Form\ChangeRoleType;
use App\Repository\UsersRepository;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * Affichage du panneau d'administration
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/admin-panel", name="admin_panel")
     */
    public function displayAdminPanel(UsersRepository $usersRepo, AnnoncesRepository $annoncesRepo)
    {
        $usersTotal = $usersRepo->countUsersNumber();
        $annoncesTotal = $annoncesRepo->countAnnoncesNumber();
        $annoncesActivesTotal = $annoncesRepo->countAnnoncesActives();
        $annoncesDesactiveesTotal = $annoncesRepo->countAnnoncesDesactivees();
        $annoncesAValiderTotal = $annoncesRepo->countAnnoncesAValider();
        $annonceAModererTotal = $annoncesRepo->countAnnoncesAModerer();
        $annonceModereesTotal = $annoncesRepo->countAnnoncesModerees();
        
        return $this->render('admin/admin_panel.html.twig', [
            'controller_name' => 'Administration',
            'usersTotal' => $usersTotal,
            'annoncesTotal' => $annoncesTotal,
            'annoncesActives' => $annoncesActivesTotal,
            'annoncesDesactivees' => $annoncesDesactiveesTotal,
            'annoncesAValider' => $annoncesAValiderTotal,
            'annoncesAModerer' => $annonceAModererTotal,
            'annoncesModerees' => $annonceModereesTotal
        ]);
    }

    /**
     * Affichage du panneau de Gestion des utilisateurs
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/gestion-users", name="gestion_users")
     */
    public function displayUsersManager(UsersRepository $usersRepo)
    {
        $usersAccounts = $usersRepo->findAll();

        return $this->render('admin/gestion_users.html.twig', [
            'controller_name' => 'Gestion Des Utilisateurs',
            'usersAccounts' => $usersAccounts
        ]);
    }

    /**
     * Changement du rôle de l'utilisateur
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/change-role-user/{id}", name="change_role_user")
     */
    public function changeRoleUser(Request $request, Users $user)
    {
        $form = $this->createForm(ChangeRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Les modifications ont été enregistrées avec succès !'
            );

            return $this->redirectToRoute('gestion_users');
        }

        return $this->render('admin/change_role.html.twig', [
            'changeRoleUserForm' => $form->createView(),
        ]);
    }

    /**
     * Suppression du compte utilisateur
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/supprimer-compte-user/{id}", name="supprimer_compte_user")
     */
    public function deleteUser(Users $user, \Swift_Mailer $mailer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        // Envoi de l'email
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('no-reply-proxicar@florentvila.com')
        ->setTo($user->getEmail())
        ->setSubject('Compte utilisateur supprimé')
        ->setBody(
            $this->renderView(
                'emails/delete_user_by_admin.html.twig',
                ['name' => $user->getName()]
            ),
            'text/html'
        );
        $mailer->send($message);

        $this->addFlash(
            'success',
            'Le compte de l\'utilisateur est supprimé.'
        );

        return $this->redirectToRoute('gestion_users');
    }

    /**
     * Affichage du panneau de Gestion des annonces du site
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/annonces-admin", name="annonces_admin")
     */
    public function displayAnnoncesManager(AnnoncesRepository $AnnoncesRepo)
    {
        $allAnnonces = $AnnoncesRepo->findAll();

        return $this->render('admin/annonces_admin.html.twig', [
            'controller_name' => 'Toutes les annonces publiées',
            'allAnnonces' => $allAnnonces
        ]);
    }

    /**
     * Validation d'une annonce d'un utilisateur
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/valid-annonce-user/{id}", name="valid_annonce_user")
     */
    public function validAnnonce(Annonces $annonce, \Swift_Mailer $mailer)
    {
        $annonce->setAnnAValider(false);
        $annonce->setAnnActive(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($annonce);
        $entityManager->flush();

        // Envoi de l'email
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('no-reply-proxicar@florentvila.com')
        ->setTo($annonce->getUsers()->getEmail())
        ->setSubject('Validation de votre annonce')
        ->setBody(
            $this->renderView(
                'emails/valid_annonce.html.twig',
                ['name' => $annonce->getUsers()->getName(), 'anntitre' => $annonce->getAnnTitre()]
            ),
            'text/html'
        );
        $mailer->send($message);

        $this->addFlash(
            'success',
            'Annonce validée.'
        );

        return $this->redirectToRoute('annonces_admin');
    }

    /**
     * Modération d'une annonce d'un utilisateur
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/moderer-annonce-user/{id}", name="moderer_annonce_user")
     */
    public function modererAnnonce(Annonces $annonce)
    {
        $annonce->setAnnSignaler(false);
        $annonce->setAnnModeree(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($annonce);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Annonce modérée.'
        );

        return $this->redirectToRoute('annonces_admin');
    }

    /**
     * Suppression d'une annonce d'un utilisateur
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/delete-annonce-admin/{id}", name="delete_annonce_admin")
     */
    public function deleteAnnonce(Annonces $annonce, \Swift_Mailer $mailer)
    {
        // Procédure de suppression des images...
        $images = $annonce->getImages();
        $deleteImg = '';  // On initialise la variable
        foreach ($images as $image) {
            // Suppression du Stockage sur Disque Dur
            $fichier = $image->getImageName();
            $deleteImg = unlink($this->getParameter('images_annonce') . "/" . $fichier);
        }
    
        // Suppression de l'écriture en Base de Données de l'annonce et des images liées à celle-ci.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        // Envoi de l'email
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('no-reply-proxicar@florentvila.com')
        ->setTo($annonce->getUsers()->getEmail())
        ->setSubject('Annonce supprimée')
        ->setBody(
            $this->renderView(
                'emails/delete_annonce_by_admin.html.twig',
                ['name' => $annonce->getUsers()->getName(), 'anntitre' => $annonce->getAnnTitre()]
            ),
            'text/html'
        );
        $mailer->send($message);
        
        $this->addFlash(
            'success',
            'L\'annonce de l\'utilisateur est supprimée.'
        );

        return $this->redirectToRoute('annonces_admin');
    }
}