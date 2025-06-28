<?php
// models/class/class_stock.php
class stock {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function get_vue_stock() {
        $sql = "SELECT * FROM vue_stock_fifo";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>