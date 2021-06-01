<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Form\EntrepriseType;
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

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise", name="entreprise")
     */
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }

    /**
     * @Route("/admin/create/entreprise", name="create-entreprise")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function createAsso(Request $r): Response
    {
        $entreprise = new Entreprises();

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/create-entreprise.html.twig', [
                'form' => $form->createView()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('logo')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('entreprise_logo_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $entreprise->setLogo($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entreprise);
            $em->flush();

            return $this->redirect('/accueil');
        }
    }

    /**
     * @Route("/admin/gerer/entreprise", name="gerer-entreprise")
     * 
     * @IsGranted("ROLE_ADMIN") 
     * 
     */
    public function gererEntreprises(): Response {
        $repository = $this->getDoctrine()->getRepository(Entreprises::class);
        $entreprises = $repository->findAll();

        return $this->render('admin/gerer-entreprises.html.twig', [
            'entreprises' => $entreprises
        ]);
    }


    /**
     * @Route("admin/modifier/entreprise/{id}", name="modifier-entreprise")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function modifierEntreprise($id, Request $r): Response
    {

        $repo = $this->getDoctrine()->getRepository(Entreprises::class);
        $entreprise = $repo->find($id);

        $oldImage = $entreprise->getLogo();

        if (empty($entreprise)) throw new NotFoundHttpException();

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/update-entreprise.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'id' => $entreprise->getId()
            ]);
        } else {

            // Je vais déplacer le fichier uploadé
            $image = $form->get('logo')->getData();

            try {
                $image->move($this->getParameter('entreprise_logo_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $entreprise->setLogo($oldImage);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entreprise);
            $em->flush();

            return $this->redirect('/concept/');
        }
    }

    /**
     * @Route("admin/supprimer/entreprise/{id}", name="supprimer-entreprise")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function supprimerEntreprise($id): Response
    {

        $repo = $this->getDoctrine()->getRepository(Entreprises::class);
        $entreprise = $repo->find($id);

        if (empty($entreprise)) throw new NotFoundHttpException();

        $oldImage = $entreprise->getLogo();

        $filesystem = new Filesystem();

        $filesystem->remove($this->getParameter('entreprise_logo_directory'), $oldImage);


        $em = $this->getDoctrine()->getManager();
        $em->remove($entreprise);
        $em->flush();

        return $this->redirectToRoute('gerer_entreprise');
    }
}
