<?php
class connexion {
    private $host = "localhost";
    private $dbname = "gestion_stock";
    private $user = "root";
    private $pwd = "";
    private $con = null;

    public function getconnexion() {
        if ($this->con === null) {
            try {
                $this->con = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $this->user,
                    $this->pwd,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) { // Correction ici (PDOException mal orthographié)
                die("Une erreur s'est produite : " . $e->getMessage());
            }
        }
        return $this->con;
    }
}
?>
