<?php
class user {
    private $id;
    private $nom;
    private $postnom;
    private $prenom;
    private $pwd;
    private $idfonction;
    private $email;
    private $numero;
    private $image;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    // Accesseurs
    public function set_id($id): void { $this->id = $id; }
    public function set_nom($nom): void { $this->nom = $nom; }
    public function set_postnom($postnom): void { $this->postnom = $postnom; }
    public function set_prenom($prenom): void { $this->prenom = $prenom; }
    public function set_pwd($pwd): void { $this->pwd = $pwd; }
    public function set_idfonction($idfonction): void { $this->idfonction = $idfonction; }
    public function set_email($email): void { $this->email = $email; }
    public function set_numero($numero): void { $this->numero = $numero; }
    public function set_image($image): void { $this->image = $image; }

    // Ajouter un utilisateur
    public function add_user(): bool {
        try {
            $query = "INSERT INTO utilisateur(nom, postnom, prenom, pwd, id_fonction, email, image, numero) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([
                $this->nom,
                $this->postnom,
                $this->prenom,
                $this->pwd,
                $this->idfonction,
                $this->email,
                $this->image,
                $this->numero
            ]);
        } catch (PDOException $e) {
            // Log ou gestion de l'erreur ici
            return false;
        }
    }

    // Modifier un utilisateur
    public function update_user(): bool {
        try {
            $query = "UPDATE utilisateur SET nom = ?, postnom = ?, prenom = ?, pwd = ?, id_fonction = ?, email = ?, numero = ? WHERE id_utilisateur = ?";
            $stmt = $this->con->prepare($query);
            return $stmt->execute([
                $this->nom,
                $this->postnom,
                $this->prenom,
                $this->pwd,
                $this->idfonction,
                $this->email,
                $this->numero,
                $this->id
            ]);
        } catch (PDOException $e) {
            // Log ou gestion de l'erreur ici
            return false;
        }
    }

    // Récupérer tous les utilisateurs avec leurs fonctions
    public function get_user(): array {
        $query = "SELECT utilisateur.nom, utilisateur.postnom, utilisateur.prenom, fonction.nom_fonction, utilisateur.image, utilisateur.email, utilisateur.numero, utilisateur.id_utilisateur 
                  FROM utilisateur 
                  INNER JOIN fonction ON utilisateur.id_fonction = fonction.id_fonction";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vérifier la connexion (email + mot de passe)
    public function verifierConnexion($email, $motdepasse) {
        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Correction: nom exact du champ mot de passe dans la BDD
        if ($user && password_verify($motdepasse, $user['pwd'])) {
            return $user;
        }
        return false;
    }

    // Recherche utilisateur par nom ou email
    public function chercher_utilisateur($recherche): array {
        $sql = "SELECT u.*, f.nom_fonction 
                FROM utilisateur u 
                JOIN fonction f ON u.id_fonction = f.id_fonction
                WHERE u.nom LIKE :search OR u.email LIKE :search";
        $stmt = $this->con->prepare($sql);
        $stmt->execute(['search' => "%$recherche%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les fonctions
    public function get_fonction(): array {
        $query = "SELECT * FROM fonction";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un utilisateur
    public function delete_user(): bool {
        try {
            $sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    // Récupérer utilisateur par ID
    public function get_user_by_id($id): array {
        $query = "SELECT * FROM utilisateur WHERE id_utilisateur = ?";
        $stmt = $this->con->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
