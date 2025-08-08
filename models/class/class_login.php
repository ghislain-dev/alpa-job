<?php
class Login {
    private $username;
    private $password;
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function set_username($username): void {
        $this->username = $username;
    }

    public function set_password($password): void {
        $this->password = $password;
    }

    public function validate(): bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // ✅ Vérifier si c’est un client
            $queryClient = "SELECT id_client, nom, email, photo, numero, genre, pwd
                            FROM client WHERE email = ?";
            $stmt = $this->con->prepare($queryClient);
            $stmt->execute([$this->username]);
            $client = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($client && password_verify($this->password, $client['pwd'])) {
                $_SESSION['id'] = $client['id_client'];
                $_SESSION['nom'] = $client['nom'];
                $_SESSION['email'] = $client['email'];
                $_SESSION['photo'] = $client['photo'];
                $_SESSION['numero'] = $client['numero'];
                $_SESSION['genre'] = $client['genre'];
                $_SESSION['role'] = 'client';
                return true;
            }

            // ✅ Sinon, vérifier si c’est un utilisateur interne (ex : admin, comptable)
            $queryUser = "SELECT u.id_utilisateur, u.nom, u.postnom, u.prenom, u.email, u.pwd, u.numero, u.image, f.nom_fonction
                          FROM utilisateur u
                          LEFT JOIN fonction f ON u.id_fonction = f.id_fonction
                          WHERE u.email = ?";
            $stmt = $this->con->prepare($queryUser);
            $stmt->execute([$this->username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($this->password, $user['pwd'])) {
                $_SESSION['id'] = $user['id_utilisateur'];
                $_SESSION['nom'] = $user['nom'] . ' ' . $user['postnom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['image'] = $user['image'];
                $_SESSION['numero'] = $user['numero'];
                $_SESSION['role'] = strtolower($user['nom_fonction']); // admin, comptable, etc.
                return true;
            }

            return false;

        } catch (PDOException $e) {
            // Optionnel : logger l'erreur pour le debug
            // error_log($e->getMessage());
            return false;
        }
    }
}
?>
