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

    public function validate() {
        // Vérifie d'abord si c'est un client
        $client = $this->verifierClient();
        if ($client) return true;

        // Sinon, vérifie si c'est un utilisateur (admin, agent, etc.)
        $user = $this->verifierUtilisateur();
        if ($user) return true;

        return false;
    }

    private function verifierClient() {
        $query = "SELECT id_client, nom, email, photo, numero, genre, pwd FROM client WHERE email = ?";
        $stmt = $this->con->prepare($query);
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

        return false;
    }

    private function verifierUtilisateur() {
        $query = "SELECT id_utilisateur, nom, postnom, prenom, email, pwd, numero, image, id_fonction FROM utilisateur WHERE email = ?";
        $stmt = $this->con->prepare($query);
        $stmt->execute([$this->username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->password, $user['pwd'])) {
            $_SESSION['id'] = $user['id_utilisateur'];
            $_SESSION['nom'] = $user['nom'] . ' ' . $user['postnom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['image'] = $user['image'];
            $_SESSION['numero'] = $user['numero'];
            $_SESSION['id_fonction'] = $user['id_fonction'];
            $_SESSION['role'] = 'utilisateur';
            return true;
        }

        return false;
    }
}
?>
