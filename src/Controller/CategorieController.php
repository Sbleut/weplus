<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/create/categorie", name="create-categorie")
     */
    public function createCategorie(Request $r): Response
    {
        $categorie = new categorie();

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/create-catego.html.twig', [
                'form' => $form->createView()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('image_catego')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('categorie_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $categorie->setImageCatego($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirect('/accueil'); 
        }
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function retrieveAll(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();

        return $this->render('accueil.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/update/categorie/{id}", name="update-categorie")
     * 
     * 
     */
    public function updateCategorie($id, Request $r): Response
    {      
        $repo = $this->getDoctrine()->getRepository(categorie::class);
        $categorie = $repo->find($id);

        if( $categorie->getProducteur() != $this->getUser() && !$this->IsGranted('ROLE_ADMIN')){

            return $this->redirectToRoute('accueil');
        }

        $oldImageCatego = $categorie->getImageCatego();

        if (empty($categorie)) throw new NotFoundHttpException();

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('modifier-categorie.html.twig', [
                'form' => $form->createView(),
                'oldImageCatego' => $oldImageCatego,
                'categorie' => $categorie
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $imageCatego = $form->get('image_catego')->getData();

            try {
                $imageCatego->move($this->getParameter('categorie_photo_directory'), $oldImageCatego);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $categorie->setphoto($oldImageCatego);

            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirect('/categorie/' . $categorie->getId());
        }
    }
}
