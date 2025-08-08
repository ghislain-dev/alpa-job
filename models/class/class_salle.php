<?php
class salle {

    private $id;
    private $nom;
    private $description;
    private $capacite;
    private $prix;
    private $disponible;
    private $image;
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    // Setters
    public function set_id($id): void { $this->id = $id; }
    public function set_nom($nom): void { $this->nom = $nom; }
    public function set_description($description): void { $this->description = $description; }
    public function set_capacite($capacite): void { $this->capacite = $capacite; }
    public function set_prix($prix): void { $this->prix = $prix; }
    public function set_disponible($disponible): void { $this->disponible = $disponible; }
    public function set_image($image): void { $this->image = $image; }

    public function add_salle(): bool {
        try {
            $query = "INSERT INTO salle (nom_salle, description, capacite, prix, disponible, photo) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([
                $this->nom, $this->description, $this->capacite,
                $this->prix, $this->disponible, $this->image
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update_salle(): bool {
        try {
            if ($this->image !== null) {
                $query = "UPDATE salle 
                          SET nom_salle = ?, description = ?, capacite = ?, prix = ?, disponible = ?, photo = ?
                          WHERE id_salle = ?";
                $stmt = $this->con->prepare($query);
                return $stmt->execute([
                    $this->nom, $this->description, $this->capacite,
                    $this->prix, $this->disponible, $this->image, $this->id
                ]);
            } else {
                $query = "UPDATE salle 
                          SET nom_salle = ?, description = ?, capacite = ?, prix = ?, disponible = ?
                          WHERE id_salle = ?";
                $stmt = $this->con->prepare($query);
                return $stmt->execute([
                    $this->nom, $this->description, $this->capacite,
                    $this->prix, $this->disponible, $this->id
                ]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function get_salle(): array {
        try {
            $query = "SELECT * FROM salle";
            $stmt = $this->con->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function delete_salle(): bool {
        try {
            $query = "DELETE FROM salle WHERE id_salle = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function get_user_by_id($id): ?array {
        try {
            $query = "SELECT * FROM salle WHERE id_salle = ?";
            $stmt = $this->con->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>
