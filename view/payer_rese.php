<?php
session_start();
require_once('../connexion/connexion.php');
require_once('../models/class/class_reservation.php');
require_once('../models/class/class_client.php');
require_once('../vendor/autoload.php'); // PHPMailer

if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$db = new connexion();
$con = $db->getconnexion();

$reservation = new Reservation($con);
$clientManager = new class_client($con);

// Traitement du paiement simulé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payer_simule'], $_POST['montant'])) {
    $id_reservation = intval($_POST['payer_simule']);
    $montant = floatval($_POST['montant']);

    if ($reservation->updateStatut($id_reservation, 'payée')) {
        $details = $reservation->getReservationById($id_reservation);
        $clientInfo = $clientManager->getClientById($details['id_client']);

        $recu = "
        <h2 style='text-align:center;'>🧾 Reçu de Paiement - Réservation #{$details['id_reservation']}</h2>
        <p><strong>Client :</strong> {$clientInfo['nom']}<br/>
        <strong>Email :</strong> {$clientInfo['email']}<br/>
        <strong>Téléphone :</strong> {$clientInfo['numero']}</p>
        <hr/>
        <p><strong>Salle :</strong> {$details['nom_salle']}<br/>
        <strong>Description :</strong> {$details['description']}<br/>
        <strong>Date :</strong> {$details['date']}<br/>
        <strong>Début :</strong> {$details['date_debut']}<br/>
        <strong>Fin :</strong> {$details['date_fin']}</p>
        <hr/>
        <p><strong>Montant payé :</strong> {$montant} FC (acompte)<br/>
        <strong>Date de paiement :</strong> " . date('Y-m-d H:i:s') . "</p>";

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alphajobbutembo@gmail.com';
        $mail->Password = 'wtvy xeol pddi xtjk';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('alphajobbutembo@gmail.com', 'Service Paiement Alpa Job');
        $mail->addAddress($clientInfo['email'], $clientInfo['nom']);
        $mail->isHTML(true);
        $mail->Subject = "Reçu de Paiement - Réservation #{$details['id_reservation']}";
        $mail->Body = $recu;

        if ($mail->send()) {
            header("Location: ../view/reservations.php?success=1");
            exit();
        } else {
            echo "❌ Paiement confirmé mais échec de l’envoi d’e-mail.";
        }
    } else {
        echo "❌ Erreur lors de la mise à jour du statut.";
    }
}

// Affichage du formulaire
if (!isset($_GET['id'])) {
    die("Réservation invalide.");
}

$id_reservation = intval($_GET['id']);
$id_client = $_SESSION['id'];

$details = $reservation->getReservationDetails($id_reservation);
$client = $clientManager->getClientById($id_client);

if (!$details || !$client) {
    die("Données indisponibles.");
}

$moitie = $details['prix_salle'] / 2;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Simulation de Paiement - Pesapal Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }
        .card {
            border-radius: 15px;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 w-100" style="max-width: 500px;">
        <div class="text-center mb-3">
            <div class="logo">🎫 Paiement Réservation</div>
            <small class="text-muted">Simulation de paiement (50%)</small>
        </div>

        <div class="mb-3">
            <p><strong>Salle :</strong> <?= htmlspecialchars($details['nom_salle']) ?></p>
            <p><strong>Description :</strong> <?= htmlspecialchars($details['description']) ?></p>
            <p><strong>Client :</strong> <?= htmlspecialchars($client['nom']) ?></p>
            <p><strong>Téléphone :</strong> <span class="text-primary fw-bold"><?= htmlspecialchars($client['numero']) ?></span></p>
            <p><strong>Prix Total :</strong> <?= number_format($details['prix_salle'], 2) ?> FC</p>
            <p><strong>Montant à payer :</strong> <span class="text-success fw-bold"><?= number_format($moitie, 2) ?> FC</span></p>
        </div>

        <form method="post">
            <input type="hidden" name="payer_simule" value="<?= $id_reservation ?>">
            <input type="hidden" name="montant" value="<?= $moitie ?>">

            <button type="submit" class="btn btn-primary w-100">💳 Confirmer le Paiement</button>
            <a href="liste_reservations.php" class="btn btn-outline-secondary w-100 mt-2">↩️ Retour</a>
        </form>
    </div>
</div>
</body>
</html>
