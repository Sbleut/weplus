<?php

namespace App\Controller;

use App\Entity\MatosCatego;
use App\Form\MatosCategoType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MatosCategoController extends AbstractController
{
    /**
     * @Route("/admin/create/matos-catego", name="create-matos-categorie")
     */
    public function createCategorie(Request $r): Response
    {
        $categorie = new MatosCatego();

        $form = $this->createForm(MatosCategoType::class, $categorie);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/create-matos-catego.html.twig', [
                'form' => $form->createView()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('matos_catego_image')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('matos_categorie_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $categorie->setMatosCategoImage($fileName);

            $em = $this->getDoctrine()->getManager(); 
            $em->persist($categorie);
            $em->flush();

            return $this->redirect('/accueil'); 
        }
    }

    /**
     * @Route("/matos/categorie/{id}", name="matos-categorie")     * 
     * 
     */
    public function retrieveCategorie($id): Response
    {      
        $repo = $this->getDoctrine()->getRepository(MatosCatego::class);
        $categorie = $repo->find($id);

        $matos = $categorie->getMatos();       

        
        if (!empty($matos)) {
            return $this->render('matos.html.twig', [
                'matos' => $matos,
                'categorie' => $categorie
            ]);
        }
    }
}
