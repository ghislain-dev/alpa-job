<?php
session_start();
require_once('../connexion/connexion.php');
require_once('../models/class/class_reservation.php');

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    // Redirige vers la page de login
    header("Location: login.php");
    exit();
}

$db = new connexion();
$con = $db->getconnexion();
$reservation = new Reservation($con);

// Récupérer les réservations au statut "payée"
$reservations = $reservation->getReservationsByStatut("payée");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservations Payées - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/nav_admin.css">
</head>
<body>


<div class="container-fluid">
    <div class="row ">
        <?php include('nav_admin.php') ?>
        <div class="col-md-10 justify-content-center mt-5 align-items-center">
            <h3 class="text-center mb-4">📋 Réservations Payées</h3>

            <?php if (empty($reservations)) : ?>
                <div class="alert alert-warning">Aucune réservation payée trouvée.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Salle</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Heure début</th>
                                <th>Heure fin</th>
                                <th>Montant payé</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $index => $res): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($res['nom_client']) ?></td>
                                    <td><?= htmlspecialchars($res['nom_salle']) ?></td>
                                    <td><?= htmlspecialchars($res['description']) ?></td>
                                    <td><?= htmlspecialchars($res['date']) ?></td>
                                    <td><?= htmlspecialchars($res['date_debut']) ?></td>
                                    <td><?= htmlspecialchars($res['date_fin']) ?></td>
                                    <td><?= number_format($res['prix_salle'] / 2, 2) ?> FC</td>
                                    <td><?= htmlspecialchars($res['statut']) ?></td>
                                    <td>
                                        <a href="../models/controleurs/controls_rese.php?stat=<?= htmlspecialchars($res['id_reservation']) ?>" onclick="return confirm('Confirmez-vous que cette réservation est honorée ?');">
                                            <button class="btn btn-success btn-sm">✔️ Honoré</button>
                                        </a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
