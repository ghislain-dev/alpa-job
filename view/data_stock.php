<?php
header('Content-Type: application/json');

// Inclure la connexion PDO (assure-toi que $pdo est bien déclaré dans ce fichier)
require_once("../connexion/connexion.php");

 $db = new connexion();
 $con = $db->getconnexion();

try {
    // Requête SQL : récupération des produits et leur stock
    $sql = "SELECT p.nom_produit, s.quantite_disponible 
            FROM produit p
            JOIN stock s ON p.id_produit = s.id_produit";

    $stmt = $con->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
    // Pour le debug : tu peux supprimer le var_dump en production
    // var_dump($data);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
