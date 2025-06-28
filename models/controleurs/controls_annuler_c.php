<?php
require_once("../../connexion/connexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'])) {
    $id_commande = $_POST['id_commande'];

    $db = new connexion();
    $con = $db->getconnexion();

    // Marquer la commande comme annulée
    $stmt = $con->prepare("UPDATE commande SET statut_commande = 'annulée' WHERE id_commande = ? AND statut_commande != 'payée'");
    if ($stmt->execute([$id_commande])) {
        header("Location: ../../view/mes_commandes.php?success=1");
    } else {
        header("Location: mes_commandes.php?error=1");
    }
    exit;
}
