<?php
class class_client {

    private $id;
    private $nom;
    private $pwd;
    private $email;
    private $numero;
    private $image;
    private $genre;
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    // Déclaration des accesseurs
    public function set_id($id) : void { $this->id = $id; }
    public function set_nom($nom) : void { $this->nom = $nom; }
    public function set_pwd($pwd) : void { $this->pwd = $pwd; }
    public function set_genre($genre) : void { $this->genre = $genre; }
    public function set_email($email) : void { $this->email = $email; }
    public function set_numero($numero) : void { $this->numero = $numero; }
    public function set_image($image) : void { $this->image = $image; }

    // Méthode pour ajouter un client
    public function add_clients() : bool {
        try {
            $query = "INSERT INTO client (nom, email, pwd, photo, numero, genre) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->email, $this->pwd, $this->image, $this->numero, $this->genre]);
        } catch (PDOException $e) {
            // Vous pouvez aussi logger l'erreur si besoin : error_log($e->getMessage());
            return false;
        }
    }

    // Méthode pour mettre à jour un client
    public function update_clients() : bool {
        try {
            $query = "UPDATE client SET nom = ?, email = ?, pwd = ?, photo = ?, numero = ?, genre = ? WHERE id_client = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->nom, $this->email, $this->pwd, $this->image, $this->numero, $this->genre, $this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthode pour récupérer tous les clients
    public function get_clients() : array {
        try {
            $query = "SELECT * FROM client";
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

    // Méthode pour supprimer un client
    public function delete_client() : bool {
        try {
            $query = "DELETE FROM client WHERE id_client = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthode pour récupérer un client par id
    public function get_client_by_id($id) : array {
        try {
            $query = "SELECT * FROM client WHERE id_client = ?";
            $stmt = $this->con->prepare($query);
            $stmt->execute([$id]);

            $donnees = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $donnees[] = $data;
            }
            return $donnees;
        } catch (PDOException $e) {
            return [];
        }
    }

    // Variante qui récupère un seul client sous forme de tableau associatif
    public function getClientById($id_client) : ?array {
        try {
            $sql = "SELECT id_client, nom, email, numero, genre FROM client WHERE id_client = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id_client]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>
