<?php  
    require_once("../../connexion/connexion.php");
    require_once("../class/class_prix.php");

    $db = new connexion();
    $con = $db->getconnexion();

    $class_fonction = new prix($con);

    if (isset($_POST['ajouter'])) {
        $montant = htmlspecialchars($_POST['montant']);
       
        
        
        $id = isset($_POST['id']) ? $_POST['id'] : null;  // Récupérer l'ID s'il est disponible, sinon null

        $class_fonction->set_montant($montant);
        

        // Si l'ID est passé, c'est une modification, sinon un ajout
        
        if (!empty($id)) {
           
            // Modification de la fonction
            $class_fonction->set_id($id); 	
            if ($class_fonction->update_prix()) {
                $msg = "Modification effectuée avec succès";
                header("location:../../view/prix.php?message=$msg");
                exit(); // N'oublie pas de sortir après avoir redirigé
            } else {
                $msg = "Échec de la modification";
                header("location:../../view/prix.php?message=$msg");
                exit();
            }
        } else {
            // Ajout d'une nouvelle fonction
            if ($class_fonction->add_prix()) {
                $msg = "Enregistrement effectué avec succès";
                header("location:../../view/prix.php?message=$msg");
                exit();
            } else {
                $msg = "Échec de l'enregistrement";
                header("location:../../view/prix.php?message=$msg");
                exit();
            }
        }
    }

    if (isset($_GET['sup']) && !empty($_GET['sup'])) {
        $id = $_GET['sup'];
        $class_fonction->set_id($id);

        if ($class_fonction->delete_prix()) {
            $msg = "La suppression a été effectuée avec succès";
            header("location:../../view/prix.php?message=$msg");
        } else {
            $msg = "Échec de la suppression";
            header("location:../../view/prix.php?message=$msg");
        }
    }
?>
