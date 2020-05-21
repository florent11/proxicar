<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Images;
use App\Entity\Annonces;
use App\Form\CreerAnnonceType;
use App\Form\ModifAnnonceType;
use App\Form\ContactAnnonceurType;
use App\Repository\AnnoncesRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function listAnnonces(Request $request, PaginatorInterface $paginator)
    {
        $annonces_repo = $this->getDoctrine()->getRepository(Annonces::class)->findBy(['ann_a_valider' => false, 'ann_signaler' => false, 'ann_active' => true]);

        $annonces = $paginator->paginate(
            $annonces_repo, // Requête contenant les données à paginer (ici nos annonces)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );

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
        $images = $annonce->getImages();

        //Si l'Id de l'utilisateur connecté est égal à l'Id de l'utilisateur-auteur de l'annonce, on affiche l'annonce uniquement pour lui. L'admin du site peut aussi voir l'annonce.
        if ($this->isGranted('ROLE_USER') && $this->getUser() == $annonce->getUsers() || $this->isGranted('ROLE_ADMIN')) {

            return $this->render('annonce/annonce_article.html.twig', [
                'annonce' => $annonce,
                'images' => $images
            ]);
        }

        //Affichage quand aucun utilisateur n'est connecté (affichage public).
        if ($annonce->getAnnAValider()) {
            $this->addFlash(
                'error',
                'Annonce en attente de validation par l\'administrateur.'
            );

            return $this->redirectToRoute('home'); 
        }
        else if ($annonce->getAnnSignaler()) {
            $this->addFlash(
                'error',
                'Annonce en attente de modération par l\'administrateur.'
            );

            return $this->redirectToRoute('home'); 
        }
        else if (!$annonce->getAnnActive()) {
            $this->addFlash(
                'error',
                'Annonce désactivée.'
            );

            return $this->redirectToRoute('home');
        }
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
     * Formulaire pour envoyer un message à l'annonceur
     * 
     * @Route("/annonce/{slug}/contact-annonceur", name="contact_annonceur")
     */
    public function contactAnnonceur(Request $request, \Swift_Mailer $mailer, $slug)
    {
        $form = $this->createForm(ContactAnnonceurType::class);
        $form->handleRequest($request);
        
        $annonce = $this->getDoctrine()->getRepository(Annonces::class)->findOneBy(['slug' => $slug]);
        $emailAnnonceur = $annonce->getUsers()->getEmail();

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            if (empty($contact['accept_form'])) {  // Si le champ 'anti-captcha' est vide, on envoie l'email'.
                $this->addFlash(
                    'notice',
                    'Votre message a été envoyé.'
                );

                // do anything else you need here, like send an email
                $message = (new \Swift_Message('Hello Email'))
                ->setFrom('no-reply-proxicar@florentvila.com')
                ->setTo($emailAnnonceur)
                ->setReplyTo($contact['email'])
                ->setSubject('Proxi\'Car - Message concernant votre annonce')
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/contact_mail_annonceur.html.twig',
                        ['titre_annonce' => $annonce->getAnnTitre(), 'name' => $contact['name'], 'email' => $contact['email'], 'message' => $contact['message']]
                    ),
                    'text/html'
                );
                $mailer->send($message);
            }
            else {  // Si le champ 'anti-captcha' est rempli, on n'envoie pas l'email'.
                $this->addFlash(
                'error',
                'Votre message n\'a pas été envoyé.'
                );
            }
            return $this->redirectToRoute('annonce', ['slug' => $slug]);
        }
        
        return $this->render('annonce/contact_annonceur.html.twig', [
            'contactAnnonceurForm' => $form->createView(),
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

            $images = $form->get('images')->getData();
            
            if (count($images) < 8) {  // Si le nombre d'images est inférieur à 8, on envoie le formulaire
                // Ajout des images
                foreach ($images as $image) {
                    // Stockage sur Disque Dur
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();
                    $image->move($this->getParameter('images_annonce'), $fichier);

                    // Ecriture en Base de Données
                    $img = new Images;
                    $img->setImageName($fichier);
                    $annonce->addImage($img);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($annonce);
                $entityManager->flush();

                $this->addFlash(
                    'notice',
                    'L\'annonce a été soumise au modérateur pour validation.'
                );
                
                return $this->redirectToRoute('user_panel');
            }
            else {  // Sinon on regénere la vue avec un message flash et avec la saisie de l'utilisateur conservée
                $formData = $form->getData();

                $this->addFlash(
                    'error',
                    'Vous avez chargé plus de 8 photos. 8 photos maximum autorisées.'
                );

                return $this->render('annonce/creer_annonce.html.twig', [
                    'creerAnnonceForm' => $form->createView(),
                    $formData
                ]);
            }  
        }
        return $this->render('annonce/creer_annonce.html.twig', [
            'creerAnnonceForm' => $form->createView()
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
            $annonce->setAnnSignaler(false);
            $annonce->setAnnModeree(false);

            // Ajout des nouvelles images
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                // Stockage sur Disque Dur
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_annonce'), $fichier);

                // Ecriture en Base de Données
                $img = new Images;
                $img->setImageName($fichier);
                $annonce->addImage($img);
            }
            
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
            'annonce' => $annonce
        ]);
    }

    /**
    * Suppression d'une image (Route pour AJAX)
    *
    * @Route("/supprimer-image/{id}", name="supprimer_image", methods={"DELETE"})
    */
    public function removeImage(Images $image)
    {
        // Procédure de suppression des images...
        $deleteImg = '';  // On initialise la variable
        // Suppression du Stockage sur Disque Dur
        $fichier = $image->getImageName();
        $deleteImg = unlink($this->getParameter('images_annonce') . "/" . $fichier);

        // Suppression de l'écriture en Base de Données des images.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($image);
        $entityManager->flush();
        return new Response('ok');
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
        // Si l'Id de l'utilisateur connecté est égal à l'Id de l'utilisateur-auteur de l'annonce, on supprime l'annonce.
        if ($this->getUser() == $annonce->getUsers()) {
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

            $this->addFlash(
                'success',
                'Votre annonce est supprimée.'
            );

            return $this->redirectToRoute('gestion_annonces');
        }
        else { // Si les Id comparés sont différents, on affiche un message d'erreur.
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
                ->setFrom('no-reply-proxicar@florentvila.com')
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