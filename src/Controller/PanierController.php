<?php

namespace App\Controller;

use App\Entity\Matos;
use App\Form\ContactLocType;
use App\Repository\MatosRepository;
use App\Services\Panier\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * 
 * @IsGranted("ROLE_USER")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(PanierService $panierService): Response
    {
        $dataPanier = $panierService->getPanier()[0];

        $total = $panierService->getPanier()[1];

        $totalCaution = $panierService->getPanier()[2];

        $form = $this->createForm(ContactLocType::class);


        return $this->render('panier.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total,
            'totalCaution' => $totalCaution, 
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/panier/ajout", name="panier-ajout")
     */

    public function add(SessionInterface $session, Request $r, MatosRepository $matosRepository)
    {
        // On récupère le panier actuel
        $id = $r->request->get('matoId');
        $panier = $session->get('panier', []);
        $matos = $matosRepository->find($id);
        $stock = $matos->getStock();

        if (!empty($panier)) {
            $matosNumber = 0;
            $quantite = 0;
            foreach ($panier as $equipement) {
                $matosNumber++;
                if ($equipement['id'] == $id) {
                    $quantite += 1;
                }
            }
            if ($quantite >= $stock) {
                echo 'Erreur : stock maximum atteint'; 
                return $this->redirectToRoute("panier");
            }
        } else {
            $matosNumber = 0;
        }

             
        
        if(!empty($_POST['accessoires'])) {
        $accessoires_id = $_POST['accessoires'];
        } else {
            $accessoires_id = null; 
        }

        $panier = $session->get("panier", [$matosNumber => [
            'id' => $id,
            'accessoires_id' => $accessoires_id
        ]]);


        $panier[$matosNumber]['accessoires_id'] = $accessoires_id;
        $panier[$matosNumber]['id']= $id;


        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }


    /**
     * @Route("/panier/delete/{id}", name="panier-delete")
     */
    public function delete($id, SessionInterface $session)
    {
        
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/panier/delete", name="panier-delete-all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("panier");
    }
}
