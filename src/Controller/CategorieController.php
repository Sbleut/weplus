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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;


/**
 * 
 * @IsGranted("ROLE_USER")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/create/categorie", name="create-categorie")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
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
     * 
     *  
     * 
     */
    public function retrieveAll(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();

        return $this->render('/layout/_navbar.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/gerer/categorie", name="gerer-categorie")
     * 
     * @IsGranted("ROLE_ADMIN") 
     * 
     */
    public function gererCategorie(): Response {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();

        return $this->render('admin/gerer-categorie.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/update/categorie/{id}", name="update-categorie")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function updateCategorie($id, Request $r): Response
    {      
        $repo = $this->getDoctrine()->getRepository(categorie::class);
        $categorie = $repo->find($id);

        $oldImage = $categorie->getImageCatego();

        if (empty($categorie)) throw new NotFoundHttpException();

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/update-catego.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'categorie' => $categorie
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $imageCatego = $form->get('image_catego')->getData();

            try {
                $imageCatego->move($this->getParameter('categorie_image_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $categorie->setImageCatego($oldImage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirect('/admin/gerer/categorie');
        }
    }

    

    /**
     * @Route("admin/supprimer/matos/categorie/{id}", name="delete-categorie")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function supprimerCategorie($id): Response {

        $repo = $this->getDoctrine()->getRepository(Matos::class);
        $categorie = $repo->find($id);

        if (empty($categorie)) throw new NotFoundHttpException();

        $oldImage = $categorie->getImageCatego();

        $filesystem = new Filesystem();

        $filesystem->remove($this->getParameter('categorie_image_directory'), $oldImage);


        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('gerer_categorie');
    }
}
