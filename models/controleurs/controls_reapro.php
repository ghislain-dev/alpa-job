<?php
require_once('../../connexion/connexion.php');
require_once('../class/classe_reapro.php');  // ta classe Reapprovisionnement

// Connexion à la base de données
$db = new connexion();
$con = $db->getconnexion();
$reappro = new Reapprovisionnement($con);
   
// Traitement de l'ajout ou de la modification
if (isset($_POST['ajouter'])) {
    $id = htmlspecialchars($_POST['id'] ?? null);
    $quantite = htmlspecialchars($_POST['quantite_ajoutee']) ;
    $date_entre = htmlspecialchars($_POST['date_entre']) ; // Date actuelle
    $date_exp = htmlspecialchars($_POST['date_exp']) ;
    $id_produit = htmlspecialchars($_POST['id_produit']) ;
    $id_fournisseur = htmlspecialchars($_POST['id_fournisseur']) ;

    // Affectation des valeurs
    $reappro->set_quantite_ajoutee($quantite);
    $reappro->set_date_entre($date_entre);
    $reappro->set_date_exp($date_exp);
    $reappro->set_id_produit($id_produit);
    $reappro->set_id_fournisseur($id_fournisseur);

    if (!empty($id)) {
        $reappro->set_id_reapprovisionnement($id);
        if ($reappro->update_reapprovisionnement()) {
            header("Location: ../../view/reapro.php");
            exit;
        } else {
            echo "Erreur lors de la mise à jour du réapprovisionnement.";
        }
    } else {
        if ($reappro->add_reapprovisionnement()) {
            header("Location: ../../view/reapro.php");
            exit;
        } else {
            echo "Erreur lors de l'ajout du réapprovisionnement.";
        }
    }
}

// Suppression
if (isset($_GET['sup'])) {
    $id = $_GET['sup'];
    $reappro->set_id_reapprovisionnement($id);
    if ($reappro->delete_reapprovisionnement()) {
        header("Location: ../../view/reapro.php");
        exit;
    } else {
        echo "Erreur lors de la suppression du réapprovisionnement.";
    }
}
?>
