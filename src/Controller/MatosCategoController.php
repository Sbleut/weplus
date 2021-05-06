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
     * @Route("/matos/categorie/{id}", name="matos-categorie") 
     * 
     */
    public function retrieveCategorie($id): Response
    {      
        $repo = $this->getDoctrine()->getRepository(MatosCatego::class);
        $categorie = $repo->find($id);

        $matos = $categorie->getMatos();       

        
        if (!empty($matos)) {
            return $this->render('matos-categorie.html.twig', [
                'matos' => $matos,
                'categorie' => $categorie
            ]);
        }
    }

    /**
     * @Route("/admin/gerer/matos/categorie", name="gerer-matos-categorie")
     * 
     * 
     */
    public function gererCategorie(): Response {
        $repository = $this->getDoctrine()->getRepository(MatosCatego::class);
        $matosCategos = $repository->findAll();

        return $this->render('admin/gerer-matos-catego.html.twig', [
            'matosCategos' => $matosCategos
        ]);
    }

    /**
     * @Route("admin/modifier/matos/categorie/{id}", name="update-matos-categorie")
     */
    public function modifierMatosCatego($id, Request $r): Response {

        $repo = $this->getDoctrine()->getRepository(MatosCatego::class);
        $matosCatego = $repo->find($id);

        $oldImage = $matosCatego->getMatosCategoImage();

        if (empty($matosCatego)) throw new NotFoundHttpException();

        $form = $this->createForm(MatosCategoType::class, $matosCatego);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('matos/update-matos-catego.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'id' => $matosCatego->getId()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $image = $form->get('image')->getData();

            try {
                $image->move($this->getParameter('matos_catego_image_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $matosCatego->setMatosCategoImage($oldImage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($matosCatego);
            $em->flush();

            return $this->redirect('/matos/categorie/' . $matosCatego->getId());
        }
    }

    /**
     * @Route("admin/supprimer/matos/categorie/{id}", name="delete-matos-categorie")
     */
    public function supprimerMatosCatego($id): Response {

        $repo = $this->getDoctrine()->getRepository(Matos::class);
        $matosCatego = $repo->find($id);

        if (empty($matosCatego)) throw new NotFoundHttpException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($matosCatego);
        $em->flush();

        return $this->redirectToRoute('gerer_matos');
    }
}
