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

        foreach($panier as $id => $quantite){
            $matos = $matosRepository->find($id);
            $dataPanier[] = [
                "matos" => $matos,
                "quantite" => $quantite
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
        $panier = $session->get("panier", []);
        $id = $r->request->get('matoId');

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

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

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
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

        if(!empty($panier[$id])){
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
