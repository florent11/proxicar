<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $this->addFlash(
                'notice',
                'Votre message a été envoyé.'
            );

            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom($contact['email'])
            ->setTo('proxicar@florentvila.com')
            ->setSubject('Message via formulaire de contact')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/contact_mail.html.twig',
                    ['name' => $contact['name'], 'email' => $contact['email'], 'message' => $contact['message']]
                ),
                'text/html'
            );
            $mailer->send($message);

            return $this->redirectToRoute('home');
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
