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

            $options = [];

            $accessoires = $form->get('accessoires')->getData();

            foreach($accessoires as $accessoire){

            $options[] = $accessoire->getId();
            }
            
            $matos->setAccessoires($options);
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

        $accessoires = $mato->getAccessoires();
        
        if (!empty($mato)) {
            return $this->render('matos.html.twig', [
                'mato' => $mato,
                'accessoires' => $accessoires,
            ]);
        }
    }

    /**
     * @Route("admin/modifier/matos/{id}", name="modifier-matos")
     */
    public function modifierMatos($id, Request $r): Response {

        $repo = $this->getDoctrine()->getRepository(Matos::class);
        $matos = $repo->find($id);

        $oldImage = $matos->getMatosImage();

        if (empty($matos)) throw new NotFoundHttpException();

        $form = $this->createForm(matosType::class, $matos);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('matos/modifier-matos.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'id' => $matos->getId()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $image = $form->get('image')->getData();

            try {
                $image->move($this->getParameter('matos_image_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $matos->setImage($oldImage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($matos);
            $em->flush();

            return $this->redirect('/matos/' . $matos->getId());
        }
    }

    /**
     * @Route("admin/supprimer/matos/{id}", name="supprimer-matos")
     */
    public function supprimerMatos($id): Response {

        $repo = $this->getDoctrine()->getRepository(Matos::class);
        $matos = $repo->find($id);

        if (empty($matos)) throw new NotFoundHttpException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($matos);
        $em->flush();

        return $this->redirectToRoute('gerer_matoss');
    }

}
