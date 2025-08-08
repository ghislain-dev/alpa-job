<?php 
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    // Redirige vers la page de login
    header("Location: login.php");
    exit();
}
    require_once("../connexion/connexion.php");
    require_once('../models/class/class_prix.php');
    
    $db = new connexion();
    $con = $db->getconnexion();
    $affichage = new prix($con);
    $affiche = $affichage->get_prix();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Fonctions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/nav_admin.css">
</head>
<body>
   
    <div class="container-fluid bg-light ">
        <div class="row align-items-center">
            <?php include_once("nav_admin.php")?>
        <main class="col-md-8 col-lg-9 p-4">
            <div class="row g-4">
                <?php if(empty($_GET['modifier'])) : ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Fixation de prix</h6>
                            </div>
                            <div class="card-body">
                                <form action="../models/controleurs/controls_prix.php" method="post">
                                    <input type="hidden" name="id">
                                    <label class="form-label">Fonction</label>
                                    <input type="text" class="form-control mb-2" name="montant" required>
                                    <button type="submit" name="ajouter" class="btn btn-success w-100">Ajouter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif ?> 

                <?php if(!empty($_GET['modifier'])) : ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-white">
                                <h6 class="mb-0">Modifier une fonction</h6>
                            </div>
                            <div class="card-body">
                                <form action="../models/controleurs/controls_prix.php" method="post">
                                    <?php
                                        $id = $_GET['modifier'];
                                        $fonction_by_id = $affichage->get_prix_by_id($id);
                                        foreach($fonction_by_id as $echo): ?>
                                        <input type="hidden" name="id" value="<?= $echo['id_prix']?>">
                                        <label class="form-label">Fonction</label>
                                        <input type="text" class="form-control mb-2" name="montant" value="<?= $echo['montant']?>" required>
                                    
                                        <button type="submit" name="ajouter" class="btn btn-warning w-100">Modifier</button>
                                    <?php endforeach; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif ?> 

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">Liste de prix</h6>    
                        </div>
                        <div class="card-body">
                            <?php if(isset($_GET['supprimer']) && !empty($_GET['supprimer'])):
                                $id = $_GET['supprimer'];
                                $fonction_by_id = $affichage->get_prix_by_id($id);
                                foreach($fonction_by_id as $cat_by_id): ?>
                                    <div class="alert alert-danger text-center">
                                        <p>Voulez-vous vraiment supprimer <strong class="text-primary"><?= $cat_by_id['montant'] ?></strong> ?</p>
                                        <a href="../models/controleurs/controls_prix.php?sup=<?= $cat_by_id['id_prix'] ?>" class="btn btn-danger">Oui</a>
                                        <a href="fonction.php" class="btn btn-secondary">Non</a>
                                    </div>
                                <?php endforeach; 
                            endif; ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table table-bordered text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>#</th>
                                            <th>Montant</th>
                                            
                                            <th>Modifier</th>
                                            <th>Supprimer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; foreach($affiche as $aff): $i++; ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $aff['montant'] ?></td>
                                            
                                            <td><a href="prix.php?modifier=<?= $aff['id_prix'] ?>" class="btn btn-sm btn-warning">Modifier</a></td>
                                            <td><a href="prix.php?supprimer=<?= $aff['id_prix'] ?>" class="btn btn-sm btn-danger">Supprimer</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
