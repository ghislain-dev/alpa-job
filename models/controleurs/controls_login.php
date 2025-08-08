<?php
session_start();

include_once("../../connexion/connexion.php");
include_once('../class/class_login.php');

$db = new connexion();
$con = $db->getconnexion();

$class_login = new Login($con);
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = trim(htmlentities($_POST['username']));
    $password = trim(htmlentities($_POST['password']));

    $class_login->set_username($username);
    $class_login->set_password($password); 

    if ($class_login->validate()) {
        // ✅ Redirection selon le rôle
        if (isset($_SESSION['role'])) {
            switch ($_SESSION['role']) {
                case 'client':
                    header('Location: ../../view/index_client.php');
                    break;
                case 'admin':
                    header('Location: ../../view/index_admin.php');
                    break;
                case 'comptable':
                    header('Location: ../../view/index_comptable.php');
                    break;
                default:
                    header('Location: ../../view/dashboard_general.php');
                    break;
            }
        } else {
            // Aucun rôle trouvé
            $error = "Rôle introuvable.";
            header("Location: ../../view/login.php?sms=" . urlencode($error));
        }
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
        header("Location: ../../view/login.php?sms=" . urlencode($error));
        exit();
    }
}
?>
