<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Services\Panier\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StaticController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();

        return $this->render('accueil.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/devis", name="devis")
     */
    public function Devis(PanierService $panierService): Response
    {
        $dataPanier = $panierService->getPanier()[0];

        $total = $panierService->getPanier()[1];

        return $this->render('devis-variable.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total,
        ]);
    }


    /**
     * @Route("/accueil/admin", name="admin")
     * 
     * @IsGranted("ROLE_ADMIN")
     */        
    public function guideAdmin(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();

        return $this->render('admin/index.html.twig', [
            'categories' => $categories
        ]);
    }

    
}
