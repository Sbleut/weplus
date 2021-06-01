<?php

namespace App\Controller;

use App\Form\ContactLocType;
use App\Form\ContactType;
use App\Repository\MatosRepository;
use App\Services\Panier\PanierService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $r, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {

            // J'affiche la vue du formulaire
            return $this->render('/layout/_contact.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {

            $data = $form->getData();
            $message = nl2br($data['message'], false);
            $data['message'] = $message;

            switch ($data['service']) {
                case 'audiovisuel':
                    $mail = (new TemplatedEmail())
                        ->from(Address::create('<thomas@weplus.fr>'))
                        ->to('business@weplus.fr')
                        ->replyTo($data['email'])
                        ->subject($data['objet'])
                        ->htmlTemplate('mail-business.html.twig')
                        ->context([
                            'data' => $data,
                        ]);


                    $mailer->send($mail);

                    break;
                case 'photographie':
                    $mail = (new TemplatedEmail())
                        ->from(Address::create('<thomas@weplus.fr>'))
                        ->to('business@weplus.fr')
                        ->replyTo($data['email'])
                        ->subject($data['objet'])
                        ->htmlTemplate('mail-business.html.twig')
                        ->context([
                            'data' => $data,
                        ]);


                    $mailer->send($mail);

                    break;
                case 'digital':
                    $mail = (new TemplatedEmail())
                        ->from(Address::create('<thomas@weplus.fr>'))
                        ->to('business@weplus.fr')
                        ->replyTo($data['email'])
                        ->subject($data['objet'])
                        ->htmlTemplate('mail-business.html.twig')
                        ->context([
                            'data' => $data,
                        ]);


                    $mailer->send($mail);

                    break;
                case 'formation':
                    $mail = (new TemplatedEmail())
                        ->from(Address::create('<thomas@weplus.fr>'))
                        ->to('formation@weplus.fr')
                        ->replyTo($data['email'])
                        ->subject($data['objet'])
                        ->htmlTemplate('devis-variable.html.twig')
                        ->context([
                            'data' => $data,
                        ]);


                    $mailer->send($mail);

                    break;
                case 'recrutement':
                    $mail = (new TemplatedEmail())
                        ->from(Address::create('<thomas@weplus.fr>'))
                        ->to('recrutement@weplus.fr')
                        ->replyTo($data['email'])
                        ->subject($data['objet'])
                        ->htmlTemplate('devis-variable.html.twig')
                        ->context([
                            'data' => $data,
                        ]);


                    $mailer->send($mail);

                    break;
                default:
                            echo 'ERROR';
            }
            return $this->redirect('/accueil');
        }
    }


    public function mailSender($objet, $data, MailerInterface $mailer, $destinataire)
    {
        $text = 'Quelqu\'un vous a envoyé une demande de contact sur votre site. Cette personne s\'appelle ' . $data['nom'] . '.' . PHP_EOL . PHP_EOL
            . 'Voici son message : ' . PHP_EOL . PHP_EOL
            . $data['message'] . PHP_EOL . PHP_EOL
            . 'Si vous voulez lui répondre, veuillez écrire à l\'adresse : ' . $data['email'];


        $email = (new TemplatedEmail())
            ->from(Address::create('<thomas@weplus.fr>'))
            ->to($destinataire)
            ->replyTo($data['email'])
            ->subject($objet)
            ->htmlTemplate('devis')
            ->text($text);

        $mailer->send($email);
    }

    /**
     * @Route("/handleContact", name="handlecontact")
     * 
     * 
     */
    public function handleContact(Request $r, MailerInterface $mailer, PanierService $panierService)
    {

        // Service Location de matériel Mailing distribution
        $form = $this->createForm(ContactLocType::class);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {

            return $this->redirect('/accueil');
        } else {

            $data = $form->getData();

            $dataPanier = $panierService->getPanier()[0];

            $total = $panierService->getPanier()[1];

            $mail = (new TemplatedEmail())
                ->from(Address::create('<thomas@weplus.fr>'))
                ->to('thomas.sublet@gmail.com')
                ->replyTo($data['email'])
                ->subject('test')
                ->htmlTemplate('devis-variable.html.twig')
                ->context([
                    'data' => $data,
                    'dataPanier' => $dataPanier,
                    'total' => $total,
                ]);


            $mailer->send($mail);

            return $this->redirect('/accueil');
        }
    }
}
