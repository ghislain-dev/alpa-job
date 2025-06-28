<?php
require_once('../../connexion/connexion.php');
require_once('../class/class_user.php');

// Connexion à la base de données
$db = new connexion();
$con = $db->getconnexion();
$user = new user($con);

// Traitement de l'ajout ou de la modification
if (isset($_POST['ajouter'])) {
    $id         = $_POST['id'] ?? null;
    $nom        = htmlspecialchars(trim($_POST['nom']));
    $postnom    = htmlspecialchars(trim($_POST['postnom']));
    $prenom     = htmlspecialchars(trim($_POST['prenom']));
    $email      = htmlspecialchars(trim($_POST['email']));
    $numero     = htmlspecialchars(trim($_POST['numero']));
    $pwd        = $_POST['pwd'];
    $confirmer  = $_POST['confirmer'];
    $idfonction = $_POST['idfonction'];

    // Vérifie si les mots de passe correspondent
    if ($pwd !== $confirmer) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Hash du mot de passe
    $pwd_hashed = password_hash($pwd, PASSWORD_DEFAULT);

    // GESTION DE L'IMAGE
    $imageName = $_FILES['image']['name'] ?? '';
    $imageTmp  = $_FILES['image']['tmp_name'] ?? '';
    if (!empty($imageName) && is_uploaded_file($imageTmp)) {
        $imageName = uniqid('user_') . '_' . basename($imageName); // nom unique
        $imagePath = 'avatar/' . $imageName;
        move_uploaded_file($imageTmp, $imagePath);
    } else {
        $imageName = 'default.png'; // image par défaut si rien n’est uploadé
    }

    // Affectation des valeurs à l’objet user
    $user->set_nom($nom);
    $user->set_postnom($postnom);
    $user->set_prenom($prenom);
    $user->set_pwd($pwd_hashed);
    $user->set_idfonction($idfonction);
    $user->set_email($email);
    $user->set_numero($numero);
    $user->set_image($imageName);

    // Mise à jour ou ajout
    if (!empty($id)) {
        $user->set_id($id);
        if ($user->update_user()) {
            header("Location: ../../view/user.php?success=update");
            exit;
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    } else {
        if ($user->add_user()) {
            header("Location: ../../view/user.php?success=add");
            exit;
        } else {
            echo "Erreur lors de l'ajout.";
        }
    }
}

// Traitement de la suppression
if (isset($_GET['sup'])) {
    $id = intval($_GET['sup']);
    $user->set_id($id);
    if ($user->delete_user()) {
        header("Location: ../../view/user.php?success=sup");
        exit;
    } else {
        echo "Échec de suppression.";
    }
}

?>
