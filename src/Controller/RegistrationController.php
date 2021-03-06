<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $botVerif = $form->get('accept_form')->getData();
            if (empty($botVerif)) {  // Si le champ 'anti-captcha' est vide, on crée le compte utilisateur.
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setRgpdDate(new \DateTime('now', new DateTimeZone('Europe/Paris')));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'notice',
                    'Votre compte a été créé avec succès !'
                );

                // do anything else you need here, like send an email
                $message = (new \Swift_Message('Hello Email'))
                ->setFrom('no-reply-proxicar@florentvila.com')
                ->setTo($user->getEmail())
                ->setSubject('Confirmation d\'inscription - Proxi\'Car')
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/registration_confirm.html.twig',
                        ['name' => $user->getName(), 'email' => $user->getEmail()]
                    ),
                    'text/html'
                );
                $mailer->send($message);
                
                return $this->redirectToRoute('home');

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }
            else {  // Si le champ 'anti-captcha' est rempli, on ne crée pas le compte utilisateur.
                $this->addFlash(
                'error',
                'Erreur - Votre compte n\'a pas pu être créé.'
                );
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
