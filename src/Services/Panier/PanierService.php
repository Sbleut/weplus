<?php


namespace App\Services\Panier;

use App\Repository\MatosRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService {

    protected $session;
    protected $matosRepository;

    public function __construct(SessionInterface $session, MatosRepository $matosRepository) {

        $this->session = $session;
        $this->matosRepository = $matosRepository;
    }

    public function getPanier(): array {

        $panier = $this->session->get("panier", []);

        // Panier data
        $dataPanier = [];
        $total = 0;
        $totalCaution = 0;
        $totalAccessoire = 0;
        
        // Pour chaque IDproduit dans le panier on va chercher les infos relatives. 
        foreach ($panier as $equipement => [ 'id' => $id, 'accessoires_id' => $accessoires_id]) {
            $matos = $this->matosRepository->find($id);
            // Les accessoires sont liés à leurs poduit par leur ID rangés dans un tableau 
            $accessoires = null;
            if($accessoires_id == null){
                $accessoires_id[] = null;
            }
            // Pour chaque accessoire du produit on va chercher le prix 
            foreach ($accessoires_id as $acid) {
                if (!empty($acid)) {
                    $accessoire = $this->matosRepository->find($acid);
                    $accessoires[] = $accessoire;
                    $totalAccessoire += $accessoire->getPrixHt();
                } else {
                    $accessoire = 0;
                    $accessoires = null;
                }
                
            }
            $dataPanier[] = [
                "matos" => $matos,
                "accessoires" => $accessoires
            ];
            $totalCaution += $matos->getCaution();
            // Pour calculer le total de chaque produti on cherche le prix du matériel plus le prix des accessoires
            $total += ($matos->getPrixHt() + $totalAccessoire);
        }

        return [$dataPanier, $total, $totalCaution];
    }
}