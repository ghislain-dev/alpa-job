<?php
class prix {
    private $id;
    private $montant;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Accesseurs
    public function set_id($id): void { $this->id = $id; }
    public function set_montant($montant): void { $this->montant = $montant; }

    // Ajouter un prix
    public function add_prix(): bool {
        try {
            $query = "INSERT INTO prix(montant) VALUES(?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->montant]);
        } catch (PDOException $e) {
            // Optionnel : error_log($e->getMessage());
            return false;
        }
    }

    // Modifier un prix
    public function update_prix(): bool {
        try {
            $query = "UPDATE prix SET montant = ? WHERE id_prix = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->montant, $this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer tous les prix
    public function get_prix(): array {
        try {
            $query = "SELECT * FROM prix";
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

    // Supprimer un prix
    public function delete_prix(): bool {
        try {
            $query = "DELETE FROM prix WHERE id_prix = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer un prix par ID
    public function get_prix_by_id($id): ?array {
        try {
            $query = "SELECT * FROM prix WHERE id_prix = ?";
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
