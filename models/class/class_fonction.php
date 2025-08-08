<?php
class fonction {
    private $id;
    private $fonction;
    private $description;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Déclaration des accesseurs
    public function set_id($id): void { $this->id = $id; }
    public function set_fonction($fonction): void { $this->fonction = $fonction; }
    public function set_description($description): void { $this->description = $description; }

    // Ajouter une fonction
    public function add_fonction(): bool {
        try {
            $query = "INSERT INTO fonction (nom_fonction, `description`) VALUES (?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->fonction, $this->description]);
        } catch (PDOException $e) {
            // Vous pouvez logger l'erreur si besoin : error_log($e->getMessage());
            return false;
        }
    }

    // Modifier une fonction
    public function update_fonction(): bool {
        try {
            $query = "UPDATE fonction SET nom_fonction = ?, `description` = ? WHERE id_fonction = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->fonction, $this->description, $this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer toutes les fonctions
    public function get_fonction(): array {
        try {
            $query = "SELECT * FROM fonction";
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

    // Supprimer une fonction
    public function delete_fonction(): bool {
        try {
            $query = "DELETE FROM fonction WHERE id_fonction = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer une fonction par son ID
    public function get_fonction_by_id($id): ?array {
        try {
            $query = "SELECT * FROM fonction WHERE id_fonction = ?";
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
