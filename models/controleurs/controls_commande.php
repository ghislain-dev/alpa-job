<?php
session_start();
require_once("../../connexion/connexion.php");
require_once("../class/class_commande.php");

if (!isset($_SESSION['id'])) {
    die("⚠️ Vous devez être connecté pour passer une commande.");
}

if (isset($_POST['commander']) && !empty($_SESSION['panier'])) {
    $db = new connexion();
    $con = $db->getconnexion();

    $commande = new Commande($con);
    $resultat = $commande->passer_commande($_SESSION['id'], $_SESSION['panier']);

    if ($resultat['success']) {
        unset($_SESSION['panier']); // Vider le panier après succès
        header("Location: ../../view/payer_commande.php?id_commande=" . $resultat['id_commande']);
        exit;
    } else {
        // Stock insuffisant ou autre erreur
        $_SESSION['erreur_commande'] = $resultat['message'];
        header("Location: ../../view/affiche_produit.php"); // Redirige vers une page panier ou produit
        exit;
    }
} else {
    header("Location: ../../view/affiche_produit.php"); // cas sans panier
    exit;
}
