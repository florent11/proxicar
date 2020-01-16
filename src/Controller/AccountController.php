<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ProfilType;
use App\Form\ResettingType;
use App\Form\ModifPasswordType;
use App\Form\ForgotPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AccountController extends AbstractController
{
    /**
     * Affichage du panneau Utilisateur
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/user-panel", name="user_panel")
     */
    public function displayUserPanel()
    {
        return $this->render('account/user_panel.html.twig', [
            'controller_name' => 'Mon Compte Utilisateur'
        ]);
    }

    /**
     * Formulaire de Modification du profil utilisateur
     * 
     * @IsGranted("ROLE_USER")
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

            return $this->redirectToRoute('user_panel');
        }

        return $this->render('account/modif_profil.html.twig', [
            'modifUserProfilForm' => $form->createView(),
        ]);
    }

     /**
     * Formulaire de Modification du mots de passe
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/modif-password", name="edit_user_password")
     */
    public function editUserPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        $form = $this->createForm(ModifPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le nouveau mots de passe a été enregistré !'
            );

            return $this->redirectToRoute('user_panel');
        }

        return $this->render('account/modif_password.html.twig', [
            'modifUserPasswordForm' => $form->createView(),
        ]);
    }

    /**
     *  Réinitialisation d'un Mots de passe oublié
     * 
     * @Route("/forgot-password", name="forgot_user_password")
     */
    public function forgotPassword(Request $request, TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $emailAddress = $form->getData();
           $emailDb = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['email' => $emailAddress['email']]);
           
            if ($emailDb) {
                $entityManager = $this->getDoctrine()->getManager();
                $emailDb->setToken($tokenGenerator->generateToken());
                $entityManager->persist($emailDb);
                $entityManager->flush();

                // Envoi de l'email
                $message = (new \Swift_Message('Hello Email'))
                ->setFrom('proxicar@florentvila.com')
                ->setTo($emailAddress['email'])
                ->setSubject('Réinitialisation du Mots de passe')
                ->setBody(
                    $this->renderView(
                        'emails/reset_password.html.twig',
                        ['name' => $emailDb->getName(), 'id' => $emailDb->getId(), 'token' => $emailDb->getToken()]
                    ),
                    'text/html'
                );
                $mailer->send($message);

                $this->addFlash(
                'notice',
                'Un email vous a été envoyé afin de réinitialiser votre mots de passe.'
                );

                return $this->redirectToRoute('home');
            }  
            else {
                $this->addFlash(
                    'error',
                    'L\'adresse email saisie n\'existe pas dans notre base de données.'
                );
            }
        }

        return $this->render('account/forgot_password.html.twig', [
            'emailToResetPassword' => $form->createView(),
        ]);
    }

    /**
    * Traitement de l'inscription du nouveau mots de passe (Mots de passe réinitialisé)
    *
    * @Route("/reset/{id}/{token}", name="resetting")
    */
    public function resetting(Users $user, $token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($user->getToken() == $token)
        {
            $form = $this->createForm(ResettingType::class, $user);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $password = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($password);

                // réinitialisation du token à null pour qu'il ne soit plus réutilisable
                $user->setToken(null);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            
                $this->addFlash(
                    'success',
                    'Votre nouveau mots de passe a été enregistré.'
                );

                return $this->redirectToRoute('app_login');
            }

            return $this->render('account/resetting_password.html.twig', [
                'resettingForm' => $form->createView()
            ]); 
        }
        else
        {
            $this->addFlash(
                'error',
                'Le token n\'existe pas.'
            );

            return $this->redirectToRoute('app_login');
        }
    }
}