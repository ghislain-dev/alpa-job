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
        // S'assurer que la session est démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $query = "SELECT id_client, nom, email, photo, numero, genre, pwd
                  FROM client WHERE email = ?";

        $stmt = $this->con->prepare($query);
        $stmt->execute([$this->username]);
        $variable = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($variable) {
            $storedPassword = $variable['pwd'];
         
            if (password_verify($this->password, $storedPassword)) {
                $_SESSION['id'] = $variable['id_client'];  	
                $_SESSION['nom'] = $variable['nom'];
                $_SESSION['email'] = $variable['email'];
                $_SESSION['photo'] = $variable['photo'];
                $_SESSION['numero'] = $variable['numero'];
                $_SESSION['genre'] = $variable['genre'];
                
                return true;
            }
        }

        return false;
    }
}
?>
