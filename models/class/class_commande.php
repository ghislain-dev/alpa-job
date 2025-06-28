<?php
class Commande {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Vérifie si le stock est suffisant pour tous les produits
    public function stock_suffisant(array $panier): bool {
        foreach ($panier as $id_produit => $quantite) {
            $stmt = $this->con->prepare("SELECT stock_valide FROM vue_stock_fifo WHERE id_produit = ?");
            $stmt->execute([$id_produit]);
            $stock = $stmt->fetchColumn();

            if ($stock === false || $stock < $quantite) {
                return false;
            }
        }
        return true;
    }

    // Ajouter une commande et retourner son ID
    public function add_commande($id_client, $montant_total): int {
        $query = "INSERT INTO commande (montant_total, statut_commande, datecommande, id_client) 
                  VALUES (?, 'en cours', NOW(), ?)";
        $stmt = $this->con->prepare($query);
        $stmt->execute([$montant_total, $id_client]);
        return $this->con->lastInsertId();
    }

    // Ajouter les détails de la commande
    public function add_details_commande($id_commande, $id_produit, $quantite): bool {
        $query = "INSERT INTO details_commande (id_commande, id_produit, quantite) VALUES (?, ?, ?)";
        $stmt = $this->con->prepare($query);
        return $stmt->execute([$id_commande, $id_produit, $quantite]);
    }

    // Traitement complet de la commande avec contrôle de stock
    public function passer_commande($id_client, $panier): array {
        if (!$this->stock_suffisant($panier)) {
            return ['success' => false, 'message' => '❌ Stock insuffisant pour un ou plusieurs produits.'];
        }

        // Calcul du montant total
        $total = 0;
        foreach ($panier as $id_produit => $qte) {
            $stmt = $this->con->prepare("SELECT prix FROM vue_stock_fifo WHERE id_produit = ?");
            $stmt->execute([$id_produit]);
            $prix = $stmt->fetchColumn();
            $total += $prix * $qte;
        }

        $id_commande = $this->add_commande($id_client, $total);

        foreach ($panier as $id_produit => $quantite) {
            $this->add_details_commande($id_commande, $id_produit, $quantite);
        }

        return ['success' => true, 'id_commande' => $id_commande, 'message' => '✅ Commande enregistrée avec succès.'];
    }
}
?>
