<?php  
    require_once("../../connexion/connexion.php");
    require_once("../class/class_fonction.php");

    $db = new connexion();
    $con = $db->getconnexion();

    $class_fonction = new fonction($con);

    if (isset($_POST['ajouter'])) {
        $fonction = htmlspecialchars($_POST['fonction']);
        $description = htmlspecialchars($_POST['description']);
        
        // Vérifier si un ID est fourni pour la modification
        $id = isset($_POST['id']) ? $_POST['id'] : null;  // Récupérer l'ID s'il est disponible, sinon null

        $class_fonction->set_fonction($fonction);
        $class_fonction->set_description($description);

        // Si l'ID est passé, c'est une modification, sinon un ajout
        if (!empty($id)) {
            // Modification de la fonction
            $class_fonction->set_id($id);
            if ($class_fonction->update_fonction()) {
                $msg = "Modification effectuée avec succès";
                header("location:../../view/fonction.php?message=$msg");
                exit(); // N'oublie pas de sortir après avoir redirigé
            } else {
                $msg = "Échec de la modification";
                header("location:../../view/fonction.php?message=$msg");
                exit();
            }
        } else {
            // Ajout d'une nouvelle fonction
            if ($class_fonction->add_fonction()) {
                $msg = "Enregistrement effectué avec succès";
                header("location:../../view/fonction.php?message=$msg");
                exit();
            } else {
                $msg = "Échec de l'enregistrement";
                header("location:../../view/fonction.php?message=$msg");
                exit();
            }
        }
    }

    if (isset($_GET['sup']) && !empty($_GET['sup'])) {
        $id = $_GET['sup'];
        $class_fonction->set_id($id);

        if ($class_fonction->delete_fonction()) {
            $msg = "La suppression a été effectuée avec succès";
            header("location:../../view/fonction.php?message=$msg");
        } else {
            $msg = "Échec de la suppression";
            header("location:../../view/fonction.php?message=$msg");
        }
    }
?>
