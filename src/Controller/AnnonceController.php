<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Annonces;
use App\Form\CreerAnnonceType;
use App\Form\ModifAnnonceType;
use App\Repository\AnnoncesRepository;
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

        //Si l'Id de l'utilisateur connecté est égal à l'Id de l'utilisateur-auteur de l'annonce, on affiche l'annonce uniquement pour lui. L'admin du site peut aussi voir l'annonce.
        if ($this->getUser() == $annonce->getUsers() || $this->getUser()->getRoles(['ROLE_ADMIN'])) {
            return $this->render('annonce/annonce_article.html.twig', [
                'annonce' => $annonce
            ]);
        } 

        //Affichage quand aucun utilisateur n'est connecté (affichage public).
        if ($annonce->getAnnAValider(true)) {
            $this->addFlash(
                'error',
                'Annonce en attente de validation par l\'administrateur.'
            );

            return $this->redirectToRoute('home'); 
        }
        else if ($annonce->getAnnSignaler(true)) {
            $this->addFlash(
                'error',
                'Annonce en attente de modération par l\'administrateur.'
            );

            return $this->redirectToRoute('home'); 
        }
        /*else if ($annonce->getAnnActive(false)) {
            $this->addFlash(
                'error',
                'Annonce désactivée.'
            );

            return $this->redirectToRoute('home');
        }*/
        else {
            return $this->render('annonce/annonce_article.html.twig', [
            'annonce' => $annonce
            ]);  
        }
    }

    /**
     * Signaler une annonce
     * 
     * @Route("/signaler-annonce/{id}", name="signaler_annonce")
     */
    public function signalerAnnonce($id)
    {
        $annonce = $this->getDoctrine()->getRepository(Annonces::class)->find($id);

        if ($annonce->getAnnSignaler(true)) {
            $this->addFlash(
                'error',
                'L\'annonce a déjà été signalée à l\'administrateur.'
            );

            return $this->redirectToRoute('home'); 
        }
        else {
            $annonce->setAnnSignaler(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'annonce est signalée à l\'administrateur.
                Par sécurité, celle-ci n\'est plus affichée jusqu\'à sa modération.'
            );

            return $this->redirectToRoute('home'); 
        } 
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

        return $this->render('account/gestion_annonces.html.twig', [
            'controller_name' => 'Gestion Des Annonces',
            'user_annonces' => $userAnnonces
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
            $annonce->setAnnDate(new \DateTime('now', new DateTimeZone('Europe/Paris')));
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

            return $this->redirectToRoute('gestion_annonces');
        }
        return $this->render('annonce/modif_annonce.html.twig', [
            'modifAnnonceForm' => $form->createView(),
        ]);
    }

    /**
     * Suppression d'une annonce
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/supprimer-annonce/{id}", name="supprimer_annonce")
     */
    public function deleteAnnonce(Annonces $annonce)
    {
        //Si l'Id de l'utilisateur connecté est égal à l'Id de l'utilisateur-auteur de l'annonce, on supprime l'annonce.
        if ($this->getUser() == $annonce->getUsers()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre annonce est supprimée.'
            );

            return $this->redirectToRoute('gestion_annonces');
        }
        else { //Si les Id comparés sont différents, on affiche un message d'erreur.
            $this->addFlash(
                'error',
                'Erreur - L\'annonce que vous souhaitez supprimer appartient à un utilisateur du site.'
            );

            return $this->redirectToRoute('home');
        }
    }

     /**
     * Désactivation automatique des annonces au bout de 30 jours
     * (Tâche CRON)
     * 
     * @Route("/auto-disable-annonces", name="auto_disabling_annonces")
     * 
     */
    public function autoDisablingAnnonces(AnnoncesRepository $annoncesRepo, \Swift_Mailer $mailer)
    {
        $annonces = $annoncesRepo->findBy(['ann_active' => true]);

        foreach($annonces as $annonce) { 
            $annDateDiff = date_diff(new DateTime(), $annonce->getAnnDate());

            if ($annDateDiff->format("%a") >= '30') {
                $annonce->setAnnActive(false);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($annonce);
                $entityManager->flush(); 

                //Envoi d'un email à l'utilisateur
                $user = $annonce->getUsers(); // On récupère les infos de l'utilisateur de l'annonce

                $message = (new \Swift_Message('Hello Email'))
                ->setFrom('proxicar@florentvila.com')
                ->setTo($user->getEmail())
                ->setSubject('Désactivation automatique de votre annonce')
                ->setBody(
                    $this->renderView(
                        'emails/annonce_auto_disable.html.twig',
                        ['name' => $user->getName(), 'annTitre' => $annonce->getAnnTitre()]
                    ),
                    'text/html'
                );
                $mailer->send($message);
            }
        }
        return new Response("Désactivation des annonces supérieures à 30 jours.");
    }

     /**
     * Renouvellement d'une annonce
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/renouveler-annonce/{id}", name="renouveler_annonce")
     * 
     */
    public function renewAnnonce(Annonces $annonce)
    {
        //Si l'Id de l'utilisateur connecté est égal à l'Id de l'utilisateur-auteur de l'annonce, on renouvelle l'annonce.
        if ($this->getUser()->getId() == $annonce->getUsers()->getId()) {
            $annonce->setAnnActive(true);
            $annonce->setAnnDate(new \DateTime('now', new DateTimeZone('Europe/Paris')));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre annonce est renouvelée.'
            );

            return $this->redirectToRoute('user_panel');
        }
        else { //Si les Id comparés sont différents, on affiche un message d'erreur.
            $this->addFlash(
                'error',
                'Erreur - L\'annonce que vous souhaitez renouveler appartient à un utilisateur du site ou n\'existe pas.'
            );

            return $this->redirectToRoute('home');
        }
    }

     /**
     * Suppression automatique des annonces au bout de 60 jours
     * (Tâche CRON)
     * 
     * @Route("/auto-delete-annonces", name="auto_deleting_annonces")
     * 
     */
    public function autoDeletingAnnonces(AnnoncesRepository $annoncesRepo)
    {
        $annonces = $annoncesRepo->findBy(['ann_active'=>false]);
        foreach($annonces as $annonce) { 
            $annDateDiff = date_diff(new DateTime(), $annonce->getAnnDate());

            if ($annDateDiff->format("%a") >= '60') {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($annonce);
                $entityManager->flush(); 
            }
        }
        return new Response("Suppression des annonces supérieures à 60 jours.");
    }
}