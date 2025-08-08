<?php
class fonction {
    private $id;
    private $nom;
    private $description;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Accesseurs
    public function set_id($id): void { $this->id = $id; }
    public function set_fonction($nom): void { $this->nom = $nom; }
    public function set_description($description): void { $this->description = $description; }

    // Ajouter une catégorie
    public function add_categorie(): bool {
        try {
            $query = "INSERT INTO categorie(nom_categorie, `description`) VALUES (?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->description]);
        } catch (PDOException $e) {
            error_log("Erreur add_categorie: " . $e->getMessage());
            return false;
        }
    }

    // Modifier une catégorie
    public function update_categorie(): bool {
        try {
            $query = "UPDATE categorie SET nom_categorie = ?, `description` = ? WHERE id_categorie = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->description, $this->id]);
        } catch (PDOException $e) {
            error_log("Erreur update_categorie: " . $e->getMessage());
            return false;
        }
    }

    // Supprimer une catégorie
    public function delete_categorie(): bool {
        try {
            $query = "DELETE FROM categorie WHERE id_categorie = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            error_log("Erreur delete_categorie: " . $e->getMessage());
            return false;
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
            error_log("Erreur get_categorie: " . $e->getMessage());
            return [];
        }
    }

    // Obtenir une catégorie par ID
    public function get_categorie_by_id($id): array {
        try {
            $query = "SELECT * FROM categorie WHERE id_categorie = ?";
            $stmt = $this->con->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur get_categorie_by_id: " . $e->getMessage());
            return [];
        }
    }
}
?>
