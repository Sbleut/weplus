<?php

namespace App\Controller;

use App\Entity\Matos;
use App\Repository\MatosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, MatosRepository $matosRepository, Request $r): Response
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => ['quantite' => $quantite, 'accessoires_id' => $accessoires_id]) {
            $matos = $matosRepository->find($id);
            foreach ($accessoires_id as $acid) {
                if (!empty($acid)) {
                    $accessoires[] = $matosRepository->find($acid);
                } else {
                    $accessoires = null;
                }
            }
            $dataPanier[] = [
                "matos" => $matos,
                "quantite" => $quantite,
                "accessoires" => $accessoires
            ];
            $total += $matos->getPrixHt() * $quantite;
        }


        return $this->render('panier.html.twig', compact("dataPanier", "total"));
    }

    /**
     * @Route("/panier/ajout", name="panier-ajout")
     */

    public function add(SessionInterface $session, Request $r)
    {
        // On récupère le panier actuel
        $id = $r->request->get('matoId');
        if (!empty($panier[$id]['quantite'])) {
            $quantite = $panier[$id]['quantite'];
        } else {
            $quantite = 0;
        }
        if (empty($accessoires_id)) {
            $accessoires_id = null;
        }
        $accessoires_id[] = $r->request->get('accessoires');

        $panier = $session->get("panier", [$id => [
            'quantite' => $quantite,
            'accessoires_id' => $accessoires_id
        ]]);        

        if (!empty($panier[$id]['quantite'])) {
            $panier[$id]['quantite']++;
        } else {
            $panier[$id]['quantite'] = 1;
        }

        $panier[$id]['accessoires_id'] = $accessoires_id;

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/panier/remove/{id}", name="panier-remove")
     */
    public function remove(Matos $matos, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $matos->getId();

        if (!empty($panier[$id]['quantite'])) {
            if ($panier[$id]['quantite'] > 1) {
                $panier[$id]['quantite']--;
            } else {
                unset($panier[$id]['quantite']);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/panier/delete/{id}", name="panier-delete")
     */
    public function delete(Matos $matos, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $matos->getId();

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
