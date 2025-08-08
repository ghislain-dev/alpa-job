<?php
class fournisseur {
    private $id;
    private $nom;
    private $numero;
    private $email;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Déclaration des accesseurs
    public function set_id($id): void { $this->id = $id; }
    public function set_nom($nom): void { $this->nom = $nom; }
    public function set_numero($numero): void { $this->numero = $numero; }
    public function set_email($email): void { $this->email = $email; }

    // Ajouter un fournisseur
    public function add_fournisseur(): bool {
        try {
            $query = "INSERT INTO fournisseur (`noms`, `numero`, `email`) VALUES (?, ?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->numero, $this->email]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Modifier un fournisseur
    public function update_fournisseur(): bool {
        try {
            $query = "UPDATE fournisseur SET noms = ?, numero = ?, email = ? WHERE id_fournisseur = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->numero, $this->email, $this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer tous les fournisseurs
    public function get_fournisseur(): array {
        try {
            $query = "SELECT * FROM fournisseur";
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

    // Supprimer un fournisseur
    public function delete_fournisseur(): bool {
        try {
            $query = "DELETE FROM fournisseur WHERE id_fournisseur = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer un fournisseur par ID
    public function get_fournisseur_by_id($id): ?array {
        try {
            $query = "SELECT * FROM fournisseur WHERE id_fournisseur = ?";
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
