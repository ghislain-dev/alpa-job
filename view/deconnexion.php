<?php
session_start(); // Démarre la session

// Supprimer toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion ou d'accueil publique
header("Location: login.php");
exit();
?>
