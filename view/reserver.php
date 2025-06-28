<?php
session_start();

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Connexion et chargement des salles
require_once('../connexion/connexion.php');
require_once('../models/class/class_salle.php');

$db = new connexion();
$con = $db->getconnexion();
$affichage = new salle($con);
$salles = $affichage->get_salle();

// Infos utilisateur connecté
$id_client = $_SESSION['id'];
$nom = $_SESSION['nom'];
$email = $_SESSION['email'];
$image = $_SESSION['photo'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation des espaces</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Barre de navigation -->
<?php include_once('navbar_clientt.php'); ?>

<div class="container mt-5">
    <h3 class="mb-4 text-center">🧭 Espaces disponibles à la réservation</h3>
    <div class="row">
        <?php foreach ($salles as $salle): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= !empty($salle['photo']) ? '../models/controleurs/avatar/' . $salle['photo'] : '../images/default_salle.jpg' ?>" class="card-img-top" alt="Image de la salle">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($salle['nom_salle']) ?></h5>

                        <p class="card-text"><?= htmlspecialchars($salle['description'] ?? "Pas de description disponible") ?></p>

                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <strong>Capacité :</strong> <?= htmlspecialchars($salle['capacite'] ?? 'Non spécifiée') ?> personnes
                            </li>
                            <li class="list-group-item">
                                <strong>Prix :</strong> <?= htmlspecialchars($salle['prix'] ?? '0') ?> FC / jour
                            </li>
                            <li class="list-group-item">
                                <strong>Type :</strong> <?= htmlspecialchars($salle['type'] ?? 'Standard') ?>
                            </li>
                        </ul>

                        <button class="btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalReserver<?= $salle['id_salle'] ?>">
                            Réserver
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- Modales de réservation -->
<?php foreach ($salles as $salle): ?>
 

<div class="modal fade" id="modalReserver<?= $salle['id_salle'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $salle['id_salle'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/controleurs/controls_reservation.php" method="post" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel<?= $salle['id_salle'] ?>">
                    Réserver : <?= htmlspecialchars($salle['nom_salle']) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_client" value="<?= htmlspecialchars($id_client) ?>">
                <input type="hidden" name="salle" value="<?= $salle['id_salle'] ?>">

                <div class="mb-3">
                    <label for="desc<?= $salle['id_salle'] ?>" class="form-label">Description</label>
                    <input type="text" class="form-control" name="nom" id="desc<?= $salle['id_salle'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="date<?= $salle['id_salle'] ?>" class="form-label">Date de réservation</label>
                    <input type="date" class="form-control" name="date" id="date<?= $salle['id_salle'] ?>" required>
                </div>

              

                <div class="mb-3">
                    <label for="date_debut<?= $salle['id_salle'] ?>" class="form-label">Date et heure de début</label>
                    <input type="datetime-local" class="form-control" name="date_debut" id="date_debut<?= $salle['id_salle'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="date_fin<?= $salle['id_salle'] ?>" class="form-label">Date et heure de fin</label>
                    <input type="datetime-local" class="form-control" name="date_fin" id="date_fin<?= $salle['id_salle'] ?>" required>
                </div>
            </div>
            


            <div class="modal-footer">
                <button type="submit" name="ajouter" class="btn btn-success">Confirmer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
       

    </div>
</div>
<?php endforeach; ?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
