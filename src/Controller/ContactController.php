<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        $form = $this->createForm(ContactType::class);     

            // J'affiche la vue du formulaire
            return $this->render('/layout/_contact.html.twig', [
                'form' => $form->createView(),
            ]);        
    }
    

    /**
     * @Route("/handleContact", name="handlecontact") 
     * 
     */
    public function handleContact(Request $r, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {

            return $this->redirect('/accueil');
        } else {      

        $data = $form->getData();

        $text = 'Quelqu\'un vous a envoyé une demande de contact sur votre site. Cette personne s\'appelle ' . $data['nom'] . '.' . PHP_EOL . PHP_EOL
                . 'Voici son message : ' . PHP_EOL . PHP_EOL
                . $data['message'] . PHP_EOL . PHP_EOL
                . 'Si vous voulez lui répondre, veuillez écrire à l\'adresse : ' . $data['email'];


            $email = new MimeEmail();
            $email->from(Address::create('test <thomas@weplus.fr>'))
                ->to('thomas.sublet@gmail.com')
                ->replyTo($data['email'])
                ->subject('Tu as reçu un mail de contact !')
                ->html('<html><body>test')
                ->text($text);

            $mailer->send($email);

            return $this->redirectToRoute('accueil');
        }

    }
}