<?php
require_once('../connexion/connexion.php');
require_once('../models/class/class_salle.php');

$db = new connexion();
$con = $db->getconnexion();
$affichage = new salle($con);
$affiche = $affichage->get_salle();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestion des Salles - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/nav_admin.css" >
</head>
<body>
   
    <div class="container-fluid bg-light ">
        <div class="row align-items-center">
            <?php include_once('nav_admin.php'); ?>
            <div class="col-md-8 col-lg-9 m-5">
            
                <h2 class="mb-4">Gestion des Salles</h2>

                <!-- Bouton Ajouter -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAjouter">
                    <i class="bi bi-plus-circle"></i> Ajouter une salle
                </button>

                <!-- Message d'alerte -->
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>

                <!-- Tableau des salles -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nom salle</th>
                                <th>Description</th>
                                <th>Capacité</th>
                                <th>Prix (FC)</th>
                                <th>Disponible</th>
                                <th>Image</th>
                                <th>Modifier</th>
                                <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($affiche as $index => $aff) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($aff['nom_salle']) ?></td>
                                    <td><?= htmlspecialchars($aff['description']) ?></td>
                                    <td><?= (int)$aff['capacite'] ?></td>
                                    <td><?= number_format($aff['prix'], 2, ',', ' ') ?></td>
                                    <td><?= $aff['disponible'] ? 'Oui' : 'Non' ?></td>
                                    <td>
                                        <?php if (!empty($aff['photo'])): ?>
                                            <img src="../models/controleurs/avatar/<?= htmlspecialchars($aff['photo']) ?>" alt="Image salle" width="50" />
                                        <?php else: ?>
                                            <span class="text-muted">Aucune image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button 
                                            class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalModifier"
                                            data-id="<?= $aff['id_salle'] ?>"
                                            data-nom="<?= htmlspecialchars($aff['nom_salle'], ENT_QUOTES) ?>"
                                            data-description="<?= htmlspecialchars($aff['description'], ENT_QUOTES) ?>"
                                            data-capacite="<?= $aff['capacite'] ?>"
                                            data-prix="<?= $aff['prix'] ?>"
                                            data-disponible="<?= $aff['disponible'] ?>"
                                            data-photo="<?= htmlspecialchars($aff['photo'], ENT_QUOTES) ?>"
                                        >
                                            <i class="bi bi-pencil-square"></i> Modifier
                                        </button>
                                    </td>
                                    <td>
                                        <a href="../models/controleurs/controls_salle.php?sup=<?= $aff['id_salle'] ?>" 
                                        onclick="return confirm('Confirmer la suppression ?')"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Ajouter -->
    <div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form action="../models/controleurs/controls_salle.php" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjouterLabel">Ajouter une salle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nomAjout" class="form-label">Nom salle</label>
                        <input type="text" name="nom" class="form-control" id="nomAjout" required>
                    </div>
                    <div class="mb-3">
                        <label for="descAjout" class="form-label">Description</label>
                        <textarea name="description" id="descAjout" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="capaciteAjout" class="form-label">Capacité</label>
                        <input type="number" name="capacite" id="capaciteAjout" class="form-control" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="prixAjout" class="form-label">Prix (FC)</label>
                        <input type="number" name="prix" id="prixAjout" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="disponibleAjout" class="form-label">Disponible</label>
                        <select name="disponible" id="disponibleAjout" class="form-select">
                            <option value="1" selected>Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="imageAjout" class="form-label">Image</label>
                        <input type="file" name="image" id="imageAjout" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="ajouter" class="btn btn-success">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier -->
    <div class="modal fade" id="modalModifier" tabindex="-1" aria-labelledby="modalModifierLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form action="../models/controleurs/controls_salle.php" method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierLabel">Modifier la salle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="modId" />
                    <div class="mb-3">
                        <label for="modNom" class="form-label">Nom salle</label>
                        <input type="text" name="nom" class="form-control" id="modNom" required>
                    </div>
                    <div class="mb-3">
                        <label for="modDescription" class="form-label">Description</label>
                        <textarea name="description" id="modDescription" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="modCapacite" class="form-label">Capacité</label>
                        <input type="number" name="capacite" id="modCapacite" class="form-control" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="modPrix" class="form-label">Prix (FC)</label>
                        <input type="number" name="prix" id="modPrix" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="modDisponible" class="form-label">Disponible</label>
                        <select name="disponible" id="modDisponible" class="form-select">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modImage" class="form-label">Changer l'image</label>
                        <input type="file" name="image" id="modImage" class="form-control" accept="image/*">
                        <div id="currentImage" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="ajouter" class="btn btn-primary">Modifier</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    ></script>

    <script>
    // Passer les données à la modal Modifier
    var modalModifier = document.getElementById('modalModifier');
    modalModifier.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;

        var id = button.getAttribute('data-id');
        var nom = button.getAttribute('data-nom');
        var description = button.getAttribute('data-description');
        var capacite = button.getAttribute('data-capacite');
        var prix = button.getAttribute('data-prix');
        var disponible = button.getAttribute('data-disponible');
        var photo = button.getAttribute('data-photo');

        modalModifier.querySelector('#modId').value = id;
        modalModifier.querySelector('#modNom').value = nom;
        modalModifier.querySelector('#modDescription').value = description;
        modalModifier.querySelector('#modCapacite').value = capacite;
        modalModifier.querySelector('#modPrix').value = prix;
        modalModifier.querySelector('#modDisponible').value = disponible;

        var currentImageDiv = modalModifier.querySelector('#currentImage');
        if(photo) {
            currentImageDiv.innerHTML = `<img src="../models/controleurs/avatar/${photo}" alt="Image actuelle" width="100">`;
        } else {
            currentImageDiv.innerHTML = 'Aucune image actuelle';
        }
    });
    </script>
</body>
</html>
