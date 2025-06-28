<?php  
require_once("../../connexion/connexion.php");
require_once("../class/class_salle.php");

$db = new connexion();
$con = $db->getconnexion();

$class_salle = new salle($con);

if (isset($_POST['ajouter'])) {
    // Récupération des données
    $id = isset($_POST['id']) && !empty($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
    $nom = htmlspecialchars($_POST['nom']);
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
    $capacite = isset($_POST['capacite']) ? intval($_POST['capacite']) : null;
    $prix = isset($_POST['prix']) ? floatval($_POST['prix']) : 0.00;
    $disponible = isset($_POST['disponible']) ? intval($_POST['disponible']) : 1;

    // Gestion de l'image
    $imageName = null;
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $imageTmp = $_FILES['image']['tmp_name'];
        $imagePath = 'avatar/' . $imageName;

        move_uploaded_file($imageTmp, $imagePath);
    }

    // Setters
    $class_salle->set_nom($nom);
    $class_salle->set_description($description);
    $class_salle->set_capacite($capacite);
    $class_salle->set_prix($prix);
    $class_salle->set_disponible($disponible);
    $class_salle->set_image($imageName);

    // Modifier
    if (!empty($id)) {
        $class_salle->set_id($id);
        if ($class_salle->update_salle()) {
            $msg = "Modification effectuée avec succès";
        } else {
            $msg = "Échec de la modification";
        }
    } else {
        // Ajouter
        if ($class_salle->add_salle()) {
            $msg = "Enregistrement effectué avec succès";
        } else {
            $msg = "Échec de l'enregistrement";
        }
    }

    header("location:../../view/salle.php?message=$msg");
    exit();
}

// Suppression
if (isset($_GET['sup']) && !empty($_GET['sup'])) {
    $id = $_GET['sup'];
    $class_salle->set_id($id);

    if ($class_salle->delete_salle()) {
        $msg = "La suppression a été effectuée avec succès";
    } else {
        $msg = "Échec de la suppression";
    }
    header("location:../../view/salle.php?message=$msg");
    exit();
}
?>
