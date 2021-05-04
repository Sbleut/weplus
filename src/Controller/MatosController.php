<?php

namespace App\Controller;

use App\Entity\Matos;
use App\Form\MatosType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MatosController extends AbstractController
{
    /**
     * @Route("/admin/create/matos", name="create-matos")
     */
    public function createMatos(Request $r): Response
    {
        $matos = new Matos();

        $form = $this->createForm(MatosType::class, $matos);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/create-matos.html.twig', [
                'form' => $form->createView()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('matos_image')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('matos_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $matos->setMatosImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($matos);
            $em->flush();

            return $this->redirect('/accueil'); 
        }
    }

    /**
     * @Route("/matos/{id}", name="matos")
     */
    public function retrieveMatos($id): Response
    {      
        $repo = $this->getDoctrine()->getRepository(Matos::class);
        $mato = $repo->find($id);

        if (!empty($mato)) {
            return $this->render('matos.html.twig', [
                'mato' => $mato
            ]);
        }
    }

}
