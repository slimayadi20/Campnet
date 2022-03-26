<?php

namespace App\Controller;

use App\Form\ContactType;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $req, \Swift_Mailer $Mailer): Response
    {
        $form = $this-> createForm(ContactType::class);
        $form-> handleRequest($req);
        if($form-> isSubmitted() && $form->isValid()){
            $contact =$form->getData();
            // ici en envoie le mail
            $message= (new Swift_Message('nouveau contact'))
                // on attribut l'epéditeur
                ->setFrom($contact['email'])
                //on attribut le destinataire
                ->setTo('ihebbt@gmail.com')
                // on crée le message la vue Twig
                ->setBody($this->renderView(
                    'emails/contact.html.twig', compact('contact')
                ), 'text/html'


                );

            ; //on envoie le message
            $Mailer->send($message);
            $this->addFlash('message', 'le message a été bien envoyé ');
            // return $this-> redirectToRoute()
        }
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
