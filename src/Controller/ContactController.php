<?php

namespace App\Controller;

use App\Form\ContactLocType;
use App\Form\ContactType;
use App\Repository\MatosRepository;
use App\Services\Panier\PanierService;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $r, MailerInterface $mailer, Session $session): Response
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

            $session->getFlashBag()->add(
                'Notification',
                'Votre demande a bien été prise en compte !'
            );

            return $this->redirect('/accueil');
        }
    }

    /**
     * @Route("/handleContact", name="handlecontact")
     * 
     * 
     */
    public function handleContact(Request $r, MailerInterface $mailer, PanierService $panierService, Session $session)
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
            
            

            $nbJour = (date_timestamp_get($data['end']) - date_timestamp_get($data['start']))/ (60 * 60 * 24);
            

            if ($nbJour >= 14){
                $nbJour = (($nbJour-13) * 0.2) + 5;
            }	else{
                switch ($nbJour) {
                    case 0:
                    $nbJour = 1;
                    break;
                    case 1:
                    $nbJour = 1;
                    break;
                    case 2:
                    $nbJour = 1.5;
                    break;
                    case 3:
                    $nbJour = 2;
                    break;
                    case 4:
                    $nbJour = 2.4;
                    break;
                    case 5:
                    $nbJour = 2.7;
                    break;
                    case 6:
                    $nbJour = 3;
                    break;
                    case 7:
                    $nbJour = 3.3;
                    break;
                    case 8:
                    $nbJour = 3.6;
                    break;
                    case 9:
                    $nbJour = 3.9;
                    break;
                    case 10:
                    $nbJour = 4.2;
                    break;
                    case 11:
                    $nbJour = 4.5;
                    break;
                    case 12:
                    $nbJour = 4.8;
                    break;
                    case 13:
                    $nbJour = 5;
                    break;
                    default:
                    $nbJour = $nbJour;
                }
            }

            $mail = (new TemplatedEmail())
                ->from(Address::create('<thomas@weplus.fr>'))
                ->to('location@weplus.fr')
                ->replyTo($data['email'])
                ->subject('Demande de Location')
                ->htmlTemplate('devis-variable.html.twig')
                ->context([
                    'data' => $data,
                    'dataPanier' => $dataPanier,
                    'total' => $total,
                    'nbJour' => $nbJour,
                ]);


            $mailer->send($mail);

            // Confirmation dans l'envoie du mail pour l'user
            $session->getFlashBag()->add(
                'Notification',
                'Votre demande a bien été prise en compte !'
            );
            return $this->redirect('/accueil');
        }
    }
}
