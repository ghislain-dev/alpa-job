<?php 
    require_once("../connexion/connexion.php");
    require_once('../models/class/class_fournisseur.php');
    
    $db = new connexion();
    $con = $db->getconnexion();
    $affichage = new fournisseur ($con);
    $affiche = $affichage->get_fournisseur();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Fonctions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include_once("navbar.php")?>
    </header>
    <main class="container mt-4">
        <div class="row g-4">
            <?php if(empty($_GET['modifier'])) : ?>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Ajouter un fournisseur</h6>
                        </div>
                        <div class="card-body">
                            <form action="../models/controleurs/controls_fournisseur.php" method="post">
                                <input type="hidden" name="id">
                                <label class="form-label">Nom :</label>
                                <input type="text" class="form-control mb-2" name="nom" required placeholder="les trois noms">
                                <label class="form-label">Tel :</label>
                                <input type="text" class="form-control mb-3" name="numero" required placeholder="votre numero">
                                 <label class="form-label">Email :</label>
                                <input type="text" class="form-control mb-3" name="email" required placeholder="Email">
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
                            <h6 class="mb-0">Modifier un fournisseur </h6>
                        </div>
                        <div class="card-body">
                            <form action="../models/controleurs/controls_fournisseur.php" method="post">
                                <?php
                                    $id = $_GET['modifier'];
                                    $fonction_by_id = $affichage->get_fournisseur_by_id($id);
                                    foreach($fonction_by_id as $echo): ?>
                                    <input type="hidden" name="id" value="<?= $echo['id_fournisseur']?>">
                                    <label class="form-label">Noms :</label>
                                    <input type="text" class="form-control mb-2" name="nom" value="<?= $echo['noms']?>" required>
                                    <label class="form-label">Numero :</label>
                                    <input type="text" class="form-control mb-3" name="numero" value="<?= $echo['numero']?>" required>
                                     <label class="form-label">Email :</label>
                                    <input type="text" class="form-control mb-3" name="email" value="<?= $echo['email']?>" required>
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
                        <h6 class="mb-0">Liste des fournisseurs</h6>    
                    </div>
                    <div class="card-body">
                        <?php if(isset($_GET['supprimer']) && !empty($_GET['supprimer'])):
                            $id = $_GET['supprimer'];
                            $fonction_by_id = $affichage->get_fournisseur_by_id($id);
                            foreach($fonction_by_id as $cat_by_id): ?>
                                <div class="alert alert-danger text-center">
                                    <p>Voulez-vous vraiment supprimer <strong class="text-primary"><?= $cat_by_id['noms'] ?></strong> ?</p>
                                    <a href="../models/controleurs/controls_fournisseur.php?sup=<?= $cat_by_id['id_fournisseur'] ?>" class="btn btn-danger">Oui</a>
                                    <a href="fonction.php" class="btn btn-secondary">Non</a>
                                </div>
                            <?php endforeach; 
                        endif; ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table table-bordered text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Fonction</th>
                                        <th>Description</th>
                                        <th>Modifier</th>
                                        <th>Supprimer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; foreach($affiche as $aff): $i++; ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $aff['noms'] ?></td>
                                        <td><?= $aff['numero'] ?></td>
                                        <td><?= $aff['email'] ?></td>
                                        <td><a href="fournisseur.php?modifier=<?= $aff['id_fournisseur'] ?>" class="btn btn-sm btn-warning">Modifier</a></td>
                                        <td><a href="fournisseur.php?supprimer=<?= $aff['id_fournisseur'] ?>" class="btn btn-sm btn-danger">Supprimer</a></td>
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
