<?php
require_once("../../connexion/connexion.php");
require_once("../class/class_stock.php");

$db = new connexion();
$con = $db->getconnexion();
$stock = new stock($con);

$data = $stock->get_vue_stock();

header('Content-Type: application/json');
echo json_encode($data);
