<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\MatosCatego;
use App\Entity\Services;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    /**
     * @Route("/admin/create/service", name="create-service")
     */
    public function createService(Request $r, EntityManagerInterface $em): Response
    {
        $service = new Services();

        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($r);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('admin/create-service.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {

            // Je vais déplacer le fichier uploadé

            // On récupère l'image
            $image = $form->get('image_service')->getData();
            // On définit le nom du fichier
            $fileName =  uniqid() . '.' . $image->guessExtension();

            try {
                // On déplace le fichier
                $image->move($this->getParameter('service_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $service->setImageService($fileName);

            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }
    }

    /**
     * @Route("/services-by-catego/{id}", name="services")
     */
    public function retrieveServiceByCatego($id)
    {
        $repository = $this->getDoctrine()->getRepository(categorie::class);
        $categorie = $repository->find($id);

        $services = $categorie->getServiceCat();
        $matosCategos =  null;

        if (empty($categorie)) throw new NotFoundHttpException();

        if($categorie->getId() == 5) {
            $repository = $this->getDoctrine()->getRepository(MatosCatego::class);
            $matosCategos = $repository->findAll();
        }        

        return $this->render('services.html.twig', [
            'matosCategos' => $matosCategos,
            'services' => $services,
            'categorie' => $categorie
        ]);
    }
}
