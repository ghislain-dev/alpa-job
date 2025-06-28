<?php 
    require_once("../connexion/connexion.php");
    require_once('../models/class/class_categorie.php');
    
    $db = new connexion();
    $con = $db->getconnexion();
    $affichage = new fonction($con);
    $affiche = $affichage->get_categorie();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>categorie</title>
</head>
<body>
    <header>
        <?php
            include_once('navbar.php')
        ?>
    </header>
    <main>
        <div class="row m-5">
            <div class="col-md-5">
                <?php if( !empty($_GET['modifier'])) :?>
                        <div class="card">
                            <div class="card-header">
                                Modifier 
                            </div>
                            <div class="card-body">
                            <?php
                                        if(isset($_GET['modifier']) && !empty($_GET['modifier'])){
                                            $id=$_GET["modifier"];
                                            $produit_by_id = $affichage->get_categorie_by_id($id);
                                            foreach($produit_by_id as $cat_by_id){
                                                ?>
                                                    <div class="alert">
                                                    <form action="../models/controleurs/controls_categorie.php" method="post">
                                                        <input type="hidden" name="id" value="<?= $cat_by_id['id_categorie']?>">
                                                        <label for="" class="form-label">Nom categorie :</label>
                                                        <input type="text" class="form-control" required autocomplete="off" value="<?= $cat_by_id['nom_categorie']?>" name="nom">
                                                        <label for="" class="form-label">Description :</label>
                                                        <input type="text" class="form-control" required autocomplete="off" value="<?= $cat_by_id['description']?>" name="description">
                                                        <div class="row mt-4">
                                                            <div class="col-md-3 ">
                                                                <button type="submit" class="btn btn-info " name="ajouter">Modifier</button>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="reset" class="btn btn-danger ">vide</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php
                                            }
                                        } 
                                        ?>
                                
                            
                            </div>
                        </div>
                <?php endif ?>
                <?php if( empty($_GET['modifier'])) :?>
                        <div class="card">
                            <div class="card-header">
                                les categories de produits
                            </div>
                            <div class="card-body">
                                    <div class="alert">
                                    <form action="../models/controleurs/controls_categorie.php" method="post">
                                        <input type="hidden" name="id">
                                        <label for="" class="form-label">Nom categorie :</label>
                                        <input type="text" class="form-control" required autocomplete="off"  name="nom">
                                        <label for="" class="form-label">Description :</label>
                                        <input type="text" class="form-control" required autocomplete="off" name="description">
                                        <div class="row mt-4">
                                            <div class="col-md-2 ">
                                                <button type="submit" class="btn btn-info " name="ajouter">Ajouter </button>
                                            </div>
                                            <div class="col-md-2">
                                            <button type="reset" class="btn btn-danger ">vide</button>
                                        
                                            </div>
                                        </div>
                                    </form>
                            
                            </div>
                        </div>
                <?php endif ?>
            </div>
            
        </div>
        <div class="col-md-6">
        <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6>liste des categorie</h6>    
                                <?php
                                    if(isset($_GET['supprimer']) && !empty($_GET['supprimer'])){
                                        $id=$_GET["supprimer"];
                                        $produit_by_id = $affichage->get_categorie_by_id($id);
                                        foreach($produit_by_id as $cat_by_id){
                                            ?>
                                                <div class="alert">
                                                    <p>Voulez vous vraiment supprimer <span class="text-primary"><?= $cat_by_id['nom_categorie']  ?><a href="../models/controleurs/controls_categorie.php?sup=<?php echo $cat_by_id['id_categorie' ]?>" class="btn btn-danger" >OUI</a> <a href="categorie.php" class="btn btn-info mx-2">Non</a></span></p>
                                                </div>
                                            <?php
                                        }
                                    } 
                                    ?>
                    </div>

                    <div class="card-body">            
                        <div class="table-responsive">
                                        <table
                                            class="table table-primary"
                                        >
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Nom categorie</th>
                                                    <th scope="col">description</th>
                                                    <th>Modifier</th>
                                                    <th>Supprimer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                        $i=0;
                                                        foreach($affiche as $aff) { $i++
                                                    ?>
                                                    
                                                <tr class="">
                                                    <td><?= $i ?></td> 
                                                    <td><?= $aff['nom_categorie'] ?></td>
                                                    <td><?= $aff['description'] ?></td> 
                                                    <td><a href="categorie.php? modifier=<?= $aff['id_categorie']?>"><button class="btn btn-info">Modifier</button></a></td>
                                                    <td><a href="categorie.php? supprimer=<?= $aff['id_categorie']?>"><button class="btn btn-danger">Supprimer</button></a></td>
                                                <?php }  ?>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </main>
</body>
</html>