<?php  
require_once("../../connexion/connexion.php");
require_once("../class/class_categorie.php");

$db = new connexion();
$con = $db->getconnexion();

$class_fonction = new fonction($con);

if (isset($_POST['ajouter'])) {
    $id = htmlspecialchars($_POST['id']);
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);

    $class_fonction->set_id($id);
    $class_fonction->set_fonction($nom);
    $class_fonction->set_description($description);

    if (!empty($id)) {
        if ($class_fonction->update_categorie()) {
            $msg = "modifie";
        } else {
            $msg = "erreur_modification";
        }
    } else {
        if ($class_fonction->add_categorie()) {
            $msg = "ajoute";
        } else {
            $msg = "erreur_insertion";
        }
    }
    header("location: ../../view/categorie.php?message=$msg");
    exit;
}

if (isset($_GET['sup']) && !empty($_GET['sup'])) {
    $id = $_GET['sup'];
    $class_fonction->set_id($id);

    if ($class_fonction->delete_categorie()) {
        $msg = "supprime";
    } else {
        $msg = "erreur_suppression";
    }
    header("location: ../../view/categorie.php?message=$msg");
    exit;
}
?>
