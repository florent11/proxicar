<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));

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
                ->setTo('proxicar@florentvila.com')
                ->setReplyTo($contact['email'])
                ->setSubject('Message via formulaire de contact')
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/contact_mail.html.twig',
                        ['name' => $contact['name'], 'email' => $contact['email'], 'message' => $contact['message'], 'date' => $date->format("d.m.Y H:i")]
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
            return $this->redirectToRoute('home');
        }
        
        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
