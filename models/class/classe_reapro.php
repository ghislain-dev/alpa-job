<?php
class Reapprovisionnement {
    private $id_reapprovisionnement;
    private $quantite_ajoutee;
    private $date_entre;
    private $date_exp; // date d'expiration
    private $id_produit;
    private $id_fournisseur;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Setters
    public function set_id_reapprovisionnement($id): void { $this->id_reapprovisionnement = $id; }
    public function set_quantite_ajoutee($qte): void { $this->quantite_ajoutee = $qte; }
    public function set_date_entre($date): void { $this->date_entre = $date; }
    public function set_date_exp($datep): void { $this->date_exp = $datep; }
    public function set_id_produit($id): void { $this->id_produit = $id; }
    public function set_id_fournisseur($id): void { $this->id_fournisseur = $id; }

    // Ajouter un réapprovisionnement
    public function add_reapprovisionnement(): bool {
        try {
            $query = "INSERT INTO reapprovisionnement (quantite_ajoutee, date_exp, id_produit, id_fournisseur, date_entre, quantite_reste) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([
                $this->quantite_ajoutee,
                $this->date_exp,
                $this->id_produit,
                $this->id_fournisseur,
                $this->date_entre,
                $this->quantite_ajoutee // initialement quantité restante = quantité ajoutée
            ]);
        } catch (PDOException $e) {
            // Log ou gestion de l'erreur
            return false;
        }
    }

    // Modifier un réapprovisionnement
    public function update_reapprovisionnement(): bool {
        try {
            $query = "UPDATE reapprovisionnement 
                      SET quantite_ajoutee = ?, date_exp = ?, date_entre = ?, id_produit = ?, id_fournisseur = ? 
                      WHERE id_reapprovisionnement = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([
                $this->quantite_ajoutee,
                $this->date_exp,
                $this->date_entre,
                $this->id_produit,
                $this->id_fournisseur,
                $this->id_reapprovisionnement
            ]);
        } catch (PDOException $e) {
            // Log ou gestion de l'erreur
            return false;
        }
    }

    // Récupérer tous les fournisseurs
    public function get_fournisseur(): array {
        $query = "SELECT * FROM fournisseur";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un réapprovisionnement
    public function delete_reapprovisionnement(): bool {
        try {
            $query = "DELETE FROM reapprovisionnement WHERE id_reapprovisionnement = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id_reapprovisionnement]);
        } catch (PDOException $e) {
            // Log ou gestion de l'erreur
            return false;
        }
    }

    // Récupérer tous les réapprovisionnements (avec infos produit et fournisseur)
    public function get_all_reapprovisionnements(): array {
        $query = "SELECT r.id_reapprovisionnement, r.quantite_ajoutee, r.date_exp, r.date_entre, 
                         p.nom_produit, p.id_produit, f.noms AS nom_fournisseur 
                  FROM reapprovisionnement r 
                  LEFT JOIN produit p ON r.id_produit = p.id_produit 
                  LEFT JOIN fournisseur f ON r.id_fournisseur = f.id_fournisseur 
                  ORDER BY r.date_exp DESC";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les produits (pour formulaire par ex.)
    public function get_produit(): array {
        $query = "SELECT produit.nom_produit, produit.image, produit.id_produit, categorie.nom_categorie, categorie.id_categorie, prix.montant AS id_prix 
                  FROM categorie, produit, prix 
                  WHERE produit.id_categorie = categorie.id_categorie 
                    AND produit.id_prix = prix.id_prix";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un fournisseur par ID
    public function get_fournisseur_by_id($id): array {
        $query = "SELECT * FROM fournisseur WHERE id_fournisseur = ?";
        $stmt = $this->con->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un réapprovisionnement par ID
    public function get_reapprovisionnement_by_id($id): array {
        $query = "SELECT * FROM reapprovisionnement WHERE id_reapprovisionnement = ?";
        $stmt = $this->con->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
