<?php
session_start();

// Inclure la classe de connexion et la base de données
include_once("../../connexion/connexion.php");
include_once('../class/class_login.php');

// Crée une instance de la classe connexion
$db = new connexion();
$con = $db->getconnexion();

// Créer une instance de la classe Login
$class_login = new Login($con);

// Initialiser la variable $error
$error = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Plus besoin de vérifier 'valider' si le formulaire est bien structuré
    $username = trim(htmlentities($_POST['username']));
    $password = trim(htmlentities($_POST['password']));

    $class_login->set_username($username);
    $class_login->set_password($password); 

    if ($class_login->validate()) {
        header('Location: ../../view/index_client.php');
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
        header("Location: ../../view/login.php?sms=" . urlencode($error));
        exit();
    }
}
?>
