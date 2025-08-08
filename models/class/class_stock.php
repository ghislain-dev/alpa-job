<?php
// models/class/class_stock.php
class stock {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function get_vue_stock() {
        try {
            $sql = "SELECT * FROM vue_stock_fifo";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Tu peux loguer l'erreur ici si besoin
            return [];
        }
    }
}
?>
