<?php
session_start();

// Inclure la connexion à la base de données et la classe Login
require_once("../../connexion/connexion.php");
require_once("../class/class_login.php");

// Crée une instance de la classe connexion
$db = new connexion();
$con = $db->getconnexion();

// Créer une instance de la classe Login
$class_login = new Login($con);

// Initialiser le message d'erreur
$error = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire de manière sécurisée
    $username = trim(htmlentities($_POST['username']));
    $password = trim(htmlentities($_POST['password']));

    // Définir les valeurs dans l'objet Login
    $class_login->set_username($username);
    $class_login->set_password($password);

    // Valider l'utilisateur (client ou utilisateur interne)
    if ($class_login->validate()) {
        // Rediriger en fonction du rôle
        if ($_SESSION['role'] === 'client') {
            header('Location: ../../view/index_client.php');
        } elseif ($_SESSION['role'] === 'utilisateur') {
            header('Location: ../../view/index_admin.php'); // ou tableau de bord
        } else {
            // Si le rôle est inconnu, déconnecter et retourner au login
            session_destroy();
            header('Location: ../../view/login.php?sms=' . urlencode("Rôle inconnu."));
        }
        exit();
    } else {
        // Échec de connexion
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
        header("Location: ../../view/login.php?sms=" . urlencode($error));
        exit();
    }
} else {
    // Si la page est accédée sans soumettre le formulaire
    header("Location: ../../view/login.php");
    exit();
}
