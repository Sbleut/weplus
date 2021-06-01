<?php

namespace App\Controller;

use App\Entity\Associations;
use App\Entity\Causes;
use App\Form\AssoType;
use Doctrine\ORM\EntityManagerInterface;
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

class AssoController extends AbstractController
{
    /**
     * @Route("/asso", name="asso")
     */
    public function index(): Response
    {
        return $this->render('asso/index.html.twig', [
            'controller_name' => 'AssoController',
        ]);
    }

    /**
     * @Route("/admin/create/asso", name="create-asso")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function createAsso(Request $r, EntityManagerInterface $em): Response
    {
        $asso = new Associations();

        $form = $this->createForm(AssoType::class, $asso);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/create-asso.html.twig', [
                'form' => $form->createView()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('asso_image')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('asso_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }
            
            $asso->setAssoImage($fileName);

            /*

            $causes = $form->get('causes')->getData();

            
            $asso->addCause($causes);
            */

            $em = $this->getDoctrine()->getManager();
            $em->persist($asso);
            $em->flush();

            return $this->redirect('/accueil');
        }
    }

    /**
     * @Route("/admin/gerer/asso", name="gerer-asso")
     * 
     * @IsGranted("ROLE_ADMIN") 
     * 
     */
    public function gererAsso(): Response {
        $repository = $this->getDoctrine()->getRepository(Associations::class);
        $asso = $repository->findAll();

        return $this->render('admin/gerer-asso.html.twig', [
            'asso' => $asso
        ]);
    }


    /**
     * @Route("admin/modifier/asso/{id}", name="modifier-asso")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function modifierAsso($id, Request $r): Response
    {

        $repo = $this->getDoctrine()->getRepository(Associations::class);
        $asso = $repo->find($id);

        $oldImage = $asso->getMatosImage();

        if (empty($matos)) throw new NotFoundHttpException();

        $form = $this->createForm(CauseType::class, $asso);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/modifier-cause.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'id' => $asso->getId()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $image = $form->get('image')->getData();

            try {
                $image->move($this->getParameter('asso_image_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $matos->setAssoImage($oldImage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($asso);
            $em->flush();

            return $this->redirect('/concept/');
        }
    }

    /**
     * @Route("admin/supprimer/asso/{id}", name="supprimer-asso")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function supprimerAsso($id): Response
    {

        $repo = $this->getDoctrine()->getRepository(Associations::class);
        $asso = $repo->find($id);

        if (empty($asso)) throw new NotFoundHttpException();

        $oldImage = $asso->getAssoImage();

        $filesystem = new Filesystem();

        $filesystem->remove($this->getParameter('asso_image_directory'), $oldImage);

        $em = $this->getDoctrine()->getManager();
        $em->remove($asso);
        $em->flush();

        return $this->redirectToRoute('gerer_asso');
    }

}
