<?php  
    require_once("../../connexion/connexion.php");
    require_once("../class/class_client.php");

    $db=new connexion();
    $con=$db->getconnexion();

    $class_client =new class_client($con);

    if(isset($_POST['ajouter'])){
        $id =htmlspecialchars($_POST['id']);
        $nom =htmlspecialchars($_POST['nom']);
        $email =htmlspecialchars($_POST['email']);
        $numero =htmlspecialchars($_POST['numero']);
        $genre =htmlspecialchars($_POST['genre']);
        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
        $image ="dafaut.png";


        $class_client->set_id($id);
        $class_client->set_nom($nom);
        $class_client->set_email($email);

        $class_client->set_numero($numero);
        $class_client->set_pwd($pwd);
        $class_client->set_genre($genre);
        $class_client->set_image($image);

      

       
            
            if($class_client->update_clients()){
                $msg ="Modification effectuér avec succes";
                header("location:../../view/categorie.php?message=$msg");
               }
               else{
                $msg =" echec de l'enregistrement";
                header("location:../../view/categorie.php?message=$msg");
               }
       
            if($class_client->add_clients()){
                $msg ="l'enregistrement effectuér avec succes";
                header("location:../../view/login.php?message=$msg");
               }
               else{
                $msg =" echec de l'enregistrement";
                header("location:../../view/creer_compte.php?message=$msg");
               }
        
        
        
           

    }

    if(isset($_GET['sup']) && !empty(['sup'])){
        $id =$_GET['sup'];
        $class_client->set_id($id);

        if($class_client->delete_client()){
            $msg=$_GET['la suppression est effectue avec succes '];
            header("location:../../view/categorie.php");
        }else{
            $msg=$_GET['eche de la suppression'];
            header("location:../../view/categorie.php");
        }
    }
?>



