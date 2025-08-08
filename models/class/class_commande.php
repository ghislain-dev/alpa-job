<?php
class Commande {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Vérifie si le stock est suffisant pour tous les produits
    public function stock_suffisant(array $panier): bool {
        try {
            foreach ($panier as $id_produit => $quantite) {
                $stmt = $this->con->prepare("SELECT stock_valide FROM vue_stock_fifo WHERE id_produit = ?");
                $stmt->execute([$id_produit]);
                $stock = $stmt->fetchColumn();

                if ($stock === false || $stock < $quantite) {
                    return false;
                }
            }
            return true;
        } catch (PDOException $e) {
            // Vous pouvez logger l'erreur si besoin : error_log($e->getMessage());
            return false;
        }
    }

    // Ajouter une commande et retourner son ID
    public function add_commande($id_client, $montant_total): int {
        try {
            $query = "INSERT INTO commande (montant_total, statut_commande, datecommande, id_client) 
                      VALUES (?, 'en cours', NOW(), ?)";
            $stmt = $this->con->prepare($query);
            $stmt->execute([$montant_total, $id_client]);
            return (int)$this->con->lastInsertId();
        } catch (PDOException $e) {
            return 0; // 0 signifie qu'aucune commande n'a été insérée
        }
    }

    // Ajouter les détails de la commande
    public function add_details_commande($id_commande, $id_produit, $quantite): bool {
        try {
            $query = "INSERT INTO details_commande (id_commande, id_produit, quantite) VALUES (?, ?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$id_commande, $id_produit, $quantite]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Traitement complet de la commande avec contrôle de stock
    public function passer_commande($id_client, $panier): array {
        try {
            if (!$this->stock_suffisant($panier)) {
                return ['success' => false, 'message' => '❌ Stock insuffisant pour un ou plusieurs produits.'];
            }

            // Calcul du montant total
            $total = 0;
            foreach ($panier as $id_produit => $qte) {
                $stmt = $this->con->prepare("SELECT prix FROM vue_stock_fifo WHERE id_produit = ?");
                $stmt->execute([$id_produit]);
                $prix = $stmt->fetchColumn();

                if ($prix === false) {
                    return ['success' => false, 'message' => "❌ Produit avec ID $id_produit introuvable."];
                }

                $total += $prix * $qte;
            }

            $id_commande = $this->add_commande($id_client, $total);

            if ($id_commande === 0) {
                return ['success' => false, 'message' => '❌ Erreur lors de l’enregistrement de la commande.'];
            }

            foreach ($panier as $id_produit => $quantite) {
                if (!$this->add_details_commande($id_commande, $id_produit, $quantite)) {
                    return ['success' => false, 'message' => '❌ Erreur lors de l’ajout des détails de la commande.'];
                }
            }

            return ['success' => true, 'id_commande' => $id_commande, 'message' => '✅ Commande enregistrée avec succès.'];

        } catch (PDOException $e) {
            return ['success' => false, 'message' => '❌ Erreur technique lors du traitement de la commande.'];
        }
    }
}
?>
