<?php

namespace App\Controller;

use App\Entity\Causes;
use App\Form\CauseType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CausesController extends AbstractController
{
    /**
     * @Route("/concept", name="concept")
     */
    public function conceptRender(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Causes::class);

        $causes = $repo->findAll();

        return $this->render('concept.html.twig', [
            'causes' => $causes,
        ]);
    }

    /**
     * @Route("/cause/{id}", name="cause")
     * 
     */
    public function retrieveCause($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Causes::class);
        $cause = $repo->find($id);

        return $this->render('cause.html.twig', [
            'cause' => $cause,
        ]);
    }

    /**
     * @Route("/admin/create/cause", name="create-cause")
     */
    public function createCause(Request $r): Response
    {
        $cause = new Causes();

        $form = $this->createForm(CauseType::class, $cause);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/create-cause.html.twig', [
                'form' => $form->createView()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('imageCause')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('cause_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $cause->setImageCause($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cause);
            $em->flush();

            return $this->redirect('/accueil');
        }
    }

    /**
     * @Route("/admin/gerer/cause", name="gerer-cause")
     * 
     * 
     */
    public function gererMatos(): Response {
        $repository = $this->getDoctrine()->getRepository(Causes::class);
        $causes = $repository->findAll();

        return $this->render('admin/gerer-cause.html.twig', [
            'causes' => $causes
        ]);
    }


    /**
     * @Route("admin/modifier/cause/{id}", name="modifier-cause")
     */
    public function modifierCause($id, Request $r): Response
    {

        $repo = $this->getDoctrine()->getRepository(Causes::class);
        $cause = $repo->find($id);

        $oldImage = $cause->getImageCause();

        if (empty($matos)) throw new NotFoundHttpException();

        $form = $this->createForm(CauseType::class, $cause);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/update-cause.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'id' => $cause->getId()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $image = $form->get('image')->getData();

            try {
                $image->move($this->getParameter('cause_image_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $matos->setImage($oldImage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cause);
            $em->flush();

            return $this->redirect('/cause/' . $cause->getId());
        }
    }

    /**
     * @Route("admin/supprimer/cause/{id}", name="supprimer-cause")
     */
    public function supprimerCause($id): Response
    {

        $repo = $this->getDoctrine()->getRepository(Cause::class);
        $cause = $repo->find($id);

        if (empty($cause)) throw new NotFoundHttpException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($cause);
        $em->flush();

        return $this->redirectToRoute('gerer_cause');
    }


}
