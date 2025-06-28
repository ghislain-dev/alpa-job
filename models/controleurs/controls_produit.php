<?php
require_once('../../connexion/connexion.php');
require_once('../class/class_produit.php');

// Connexion à la base de données
$db = new connexion();
$con = $db->getconnexion();
$user = new produit($con);

// Traitement de l'ajout ou de la modification
if (isset($_POST['ajouter'])) {
    // Récupération et nettoyage des données du formulaire
    $id = $_POST['id'] ?? null;
    $nom = htmlspecialchars($_POST['nom']);
    $categorie = $_POST['idcategorie'];
    $idprix=$_POST['idprix'];
    

    // Gestion de l'image
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imagePath = 'avatar/' . $imageName;
    move_uploaded_file($imageTmp, $imagePath);

    // Validation mot de passe
   

    // Affectation des valeurs
    $user->set_nom($nom);
   
    $user->set_categorie($categorie);
    
    $user->set_image($imageName);
    $user->set_idprix($idprix);

   
    if (!empty($id)) {
        $user->set_id($id);
        if ($user->update_produit()) {
            header("Location: ../../view/produit.php");
            exit;
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    } else {
        
        if ($user->add_produit()) {
            header("Location: ../../view/produit.php");
            exit;
        } else {
            echo "Erreur lors de l'ajout.";
        }
    }
}


if (isset($_GET['sup'])) {
    $id = $_GET['sup'];
    $user->set_id($id);
    $user->delete_produit();
    header("Location: ../../view/produit.php");
    exit;
}





if (isset($_GET['sup_reapp'])) {
    $id_reappro = intval($_GET['sup_reapp']);

    // Vérifie s'il a été utilisé dans une commande
    $sqlCheck = "SELECT COUNT(*) FROM details_commande WHERE id_reapprovisionnement = ?";
    $stmtCheck = $con->prepare($sqlCheck);
    $stmtCheck->execute([$id_reappro]);
    $usedCount = $stmtCheck->fetchColumn();

    if ($usedCount > 0) {
        // Marquer comme inactif
        $sqlUpdate = "UPDATE reapprovisionnement SET statut = 'inactif' WHERE id_reapprovisionnement = ?";
        $stmt = $con->prepare($sqlUpdate);
        $stmt->execute([$id_reappro]);
        header("Location: ../../view/index_admin.php?supprime=inactif");
    } else {
        // Supprimer complètement
        $sqlDelete = "DELETE FROM reapprovisionnement WHERE id_reapprovisionnement = ?";
        $stmt = $con->prepare($sqlDelete);
        $stmt->execute([$id_reappro]);
        header("Location: ../../view/index_admin.php?supprime=ok");
    }
    exit();
}

?>
