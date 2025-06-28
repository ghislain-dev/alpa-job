<?php 
    require_once("../connexion/connexion.php");
    require_once('../models/class/class_produit.php');
    $db = new connexion();
    $con = $db->getconnexion();
    $affichage = new produit($con);
    $affiche = $affichage->get_produit();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
      body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
      padding-top: 20px;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .dashboard-card {
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
   
    </style>
</head>
<body>
    <div class="container-fluid bg-light ">
    <div class="row align-items-center">
        <?php include_once("nav_admin.php") ?>
        <div class="col-md-8 col-lg-9 p-4">
            <main>
            <div class="container mt-4">
                
                <div class="row mb-3">
                <div class="col text-end">
                    <button type="button" class="btn btn-primary" id="btnAddProduit" data-bs-toggle="modal" data-bs-target="#produitModal">
                    Ajouter un produit
                    </button>
                </div>
                </div>

                <?php if(isset($_GET['supprimer']) && !empty($_GET['supprimer'])):
                $id = $_GET['supprimer'];
                $fonction_by_id = $affichage->get_produit_by_id($id);
                foreach($fonction_by_id as $cat_by_id): ?>
                    <div class="alert alert-danger text-center">
                    <p>Voulez-vous vraiment supprimer <strong class="text-primary"><?= $cat_by_id['nom_produit'] ?></strong> ?</p>
                    <a href="../models/controleurs/controls_produit.php?sup=<?= $cat_by_id['id_produit'] ?>" class="btn btn-danger">Oui</a>
                    <a href="produit.php" class="btn btn-secondary">Non</a>
                    </div>
                <?php endforeach; 
                endif; ?>
                <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Liste des produits</h6>    
                    </div>
                    <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                        <thead class="table-primary">
                            <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Image</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; foreach($affiche as $aff): $i++; ?>
                            <tr>
                            <td><?= $i ?></td>
                            <td><?= $aff['nom_produit'] ?></td>
                            <td>
                                <?php if(!empty($aff['image'])): ?>
                                <img src="../uploads/<?= $aff['image'] ?>" alt="" width="60">
                                <?php endif; ?>
                            </td>
                            <td><?= $aff['nom_categorie'] ?></td>
                            <td><?= $aff['id_prix'] ?></td>
                            <td>
                                <button 
                                type="button" 
                                class="btn btn-warning btn-sm btnEditProduit"
                                data-id="<?= htmlspecialchars($aff['id_produit']) ?>"
                                data-nom="<?= htmlspecialchars($aff['nom_produit'], ENT_QUOTES, 'UTF-8') ?>"
                                data-image="<?= htmlspecialchars($aff['image'], ENT_QUOTES, 'UTF-8') ?>"
                                data-idcategorie="<?= htmlspecialchars($aff['id_categorie']) ?>"
                                data-idprix="<?= htmlspecialchars($aff['id_prix']) ?>"
                                data-bs-toggle="modal" 
                                data-bs-target="#produitModal"
                                >Modifier</button>
                            </td>
                            <td>
                                <a href="produit.php?supprimer=<?= $aff['id_produit'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Modal unique pour Ajouter/Modifier -->
            <div class="modal fade" id="produitModal" tabindex="-1" aria-labelledby="produitModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                <form action="../models/controleurs/controls_produit.php" method="post" enctype="multipart/form-data" id="formProduit">
                <div class="modal-header">
                <h5 class="modal-title" id="produitModalLabel">Ajouter un produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="produitId">
                    <div class="mb-3">
                    <label class="form-label">Nom produit:</label>
                    <input type="text" class="form-control" name="nom" id="produitNom" required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Image :</label>
                    <input type="file" class="form-control" name="image" id="produitImage">
                    <div id="imagePreview" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Catégorie :</label>
                    <select class="form-select" name="idcategorie" id="produitCategorie" required>
                        <option value="">Sélectionner une catégorie</option>
                        <?php 
                        $categories = $affichage->get_categorie();
                        foreach($categories as $cat){
                            echo "<option value='{$cat['id_categorie']}'>{$cat['nom_categorie']}</option>";
                        }
                        ?>
                    </select>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Prix :</label>
                    <select class="form-select" name="idprix" id="produitPrix" required>
                        <option value="">Sélectionner un prix</option>
                        <?php 
                        $prixs = $affichage->get_prix();
                        foreach($prixs as $prix){
                            echo "<option value='{$prix['id_prix']}'>{$prix['montant']}</option>";
                        }
                        ?>
                    </select>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" name="ajouter" class="btn btn-success" id="btnSubmitProduit">Ajouter</button>
                </div>
                </form>
                </div>
            </div>
            </div>
            </main>
        </div>
    </div>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajout
    document.getElementById('btnAddProduit').addEventListener('click', function() {
        document.getElementById('produitModalLabel').textContent = "Ajouter un produit";
        document.getElementById('btnSubmitProduit').textContent = "Ajouter";
        document.getElementById('formProduit').reset();
        document.getElementById('produitId').value = "";
        document.getElementById('imagePreview').innerHTML = "";
    });

    // Modification
    document.querySelectorAll('.btnEditProduit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('produitModalLabel').textContent = "Modifier un produit";
            document.getElementById('btnSubmitProduit').textContent = "Modifier";
            document.getElementById('produitId').value = this.dataset.id;
            document.getElementById('produitNom').value = this.dataset.nom;
            document.getElementById('produitCategorie').value = this.dataset.idcategorie;
            document.getElementById('produitPrix').value = this.dataset.idprix;
            document.getElementById('imagePreview').innerHTML = this.dataset.image 
                ? `<img src="../uploads/${this.dataset.image}" alt="Image actuelle" class="img-thumbnail" width="120">` 
                : "";
        });
    });
});
</script>
</body>
</html>
