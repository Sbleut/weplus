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

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;
        $totalCaution = 0;
        $totalAccessoire = 0;
        

        foreach ($panier as $equipement => [ 'id' => $id, 'accessoires_id' => $accessoires_id]) {
            $matos = $this->matosRepository->find($id);
            $accessoires = null;
            if($accessoires_id == null){
                $accessoires_id[] = null;
            }
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
            $total += ($matos->getPrixHt() + $totalAccessoire);
        }

        return [$dataPanier, $total, $totalCaution];
    }
}