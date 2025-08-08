<?php
class produit {
    private $id;
    private $nom;
    private $categorie;
    private $image;
    private $idprix;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Accesseurs
    public function set_id($id): void { $this->id = $id; }
    public function set_nom($nom): void { $this->nom = $nom; }
    public function set_categorie($categorie): void { $this->categorie = $categorie; }
    public function set_image($image): void { $this->image = $image; }
    public function set_idprix($idprix): void { $this->idprix = $idprix; }

    // Ajouter un produit
    public function add_produit(): bool {
        try {
            $query = "INSERT INTO produit(nom_produit, image, id_categorie, id_prix) VALUES (?, ?, ?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->image, $this->categorie, $this->idprix]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Modifier un produit
    public function update_produit(): bool {
        try {
            $query = "UPDATE produit SET nom_produit = ?, image = ?, id_categorie = ?, id_prix = ? WHERE id_produit = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->image, $this->categorie, $this->idprix, $this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Rechercher un produit
    public function rechercher_produit($motcle): array {
        try {
            $sql = "SELECT p.*, c.nom_categorie 
                    FROM produit p
                    JOIN categorie c ON p.id_categorie = c.id_categorie
                    WHERE p.nom_produit LIKE :motcle
                    ORDER BY p.nom_produit ASC";
            $stmt = $this->con->prepare($sql);
            $stmt->execute(['motcle' => "%$motcle%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtenir tous les produits avec infos catégorie et prix
    public function get_produit(): array {
        try {
            $query = "SELECT 
                        produit.id_produit,
                        produit.nom_produit,
                        produit.image,
                        categorie.id_categorie,
                        categorie.nom_categorie,
                        prix.id_prix,
                        prix.montant
                    FROM produit
                    INNER JOIN categorie ON produit.id_categorie = categorie.id_categorie
                    INNER JOIN prix ON produit.id_prix = prix.id_prix";
            $stmt = $this->con->prepare($query);
            $stmt->execute();

            $data = [];
            while ($dat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $dat;
            }
            return $data;
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtenir toutes les catégories
    public function get_categorie(): array {
        try {
            $query = "SELECT * FROM categorie";
            $stmt = $this->con->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtenir tous les prix
    public function get_prix(): array {
        try {
            $query = "SELECT * FROM prix";
            $stmt = $this->con->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Supprimer un produit
    public function delete_produit(): bool {
        try {
            $query = "DELETE FROM produit WHERE id_produit = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Obtenir un produit par ID
    public function get_produit_by_id($id): ?array {
        try {
            $query = "SELECT * FROM produit WHERE id_produit = ?";
            $stmt = $this->con->prepare($query);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>
