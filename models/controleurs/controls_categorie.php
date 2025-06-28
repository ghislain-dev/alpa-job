<?php  
    require_once("../../connexion/connexion.php");
    require_once("../class/class_categorie.php");

    $db=new connexion();
    $con=$db->getconnexion();

    $class_fonction =new fonction($con);

    if(isset($_POST['ajouter'])){
        $id =htmlspecialchars($_POST['id']);
        $nom =htmlspecialchars($_POST['nom']);
        $description =htmlspecialchars($_POST['description']);

        $class_fonction->set_id($id);
        $class_fonction->set_fonction($nom);
        $class_fonction->set_description($description);

      

        if (!empty($id)) {
       
             if($class_fonction->update_categorie()){
                $msg ="Modification effectuér avec succes";
                header("location:../../view/categorie.php?message=$msg");
               }
               else{
                $msg =" echec de l'enregistrement";
                header("location:../../view/categorie.php?message=$msg");
               }

    } else {
        // Ajout
        if($class_fonction->add_categorie()){
                $msg ="l'enregistrement effectuér avec succes";
                header("location:../../view/categorie.php");
               }
               else{
                $msg =" echec de l'enregistrement";
                header("location:../../view/categorie.php?message=$msg");
               }
    }
       
            
           
        
        
        
           

    }

    if(isset($_GET['sup']) && !empty(['sup'])){
        $id =$_GET['sup'];
        $class_fonction->set_id($id);

        if($class_fonction->delete_categorie()){
            $msg=$_GET['la suppression est effectue avec succes '];
            header("location:../../view/categorie.php");
        }else{
            $msg=$_GET['eche de la suppression'];
            header("location:../../view/categorie.php");
        }
    }
?>



