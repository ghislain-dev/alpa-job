<?php
require_once('../../connexion/connexion.php');
require_once('../class/class_reservation.php');

$db = new connexion();
$con = $db->getconnexion();
$reservation = new Reservation($con);

$msg = "";

// Vérifie si la requête est POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Ajouter une réservation
    if (isset($_POST['ajouter'])) {
        $reservation->setDescription($_POST['nom']);
        $reservation->setDate($_POST['date']);
        $reservation->setDateDebut($_POST['date_debut']);
        $reservation->setDateFin($_POST['date_fin']);
        $reservation->setIdClient($_POST['id_client']);
        $reservation->setIdSalle($_POST['salle']);

        if ($reservation->checkConflit()) {
            $msg = "❌ Ce créneau est déjà réservé. Choisissez une autre date.";
        } elseif ($reservation->add()) {
            $msg = "✅ Réservation effectuée avec succès.";
        } else {
            $msg = "❌ Erreur lors de l'enregistrement.";
        }

    // Modifier une réservation
    } elseif (isset($_POST['modifier'])) {
        $reservation->setId($_POST['id_reservation']);
        $reservation->setDescription($_POST['description']);
        $reservation->setDate($_POST['date']);
        $reservation->setDateDebut($_POST['date_debut']);
        $reservation->setDateFin($_POST['date_fin']);
        $reservation->setIdSalle($_POST['salle']);


        if ($reservation->checkConflit()) {
            $msg = "❌ Ce créneau est déjà réservé. Choisissez une autre date.";
        } elseif ($reservation->update()) {
            $msg = "✅ Réservation modifiée avec succès.";
        } else {
            $msg = "❌ Erreur lors de la modification.";
        }

    // Supprimer une réservation
    } elseif (isset($_POST['payer'])) {
        $reservation->setId($_POST['payer']);
        if ($reservation->payer()) { // méthode à ajouter dans ta classe
            $msg = "✅ Réservation payée avec succès.";
        } else {
            $msg = "❌ Erreur lors du paiement.";
        }
    }
    elseif (isset($_POST['payer_simule'])) {
    $id_reservation = intval($_POST['payer_simule']);
    $montant = floatval($_POST['montant']);

    if ($reservation->enregistrerPaiement($id_reservation, $montant)) {
        $msg = "✅ Paiement enregistré avec succès.";
    } else {
        $msg = "❌ Échec de l'enregistrement du paiement.";
    }

    header("Location: ../../client/liste_reservations.php?msg=" . urlencode($msg));
    exit();
}


    // Redirection avec message
    header("Location: ../../view/reservations.php?message=" . urlencode($msg));
    exit();
}

if (isset($_GET['stat'])) {
    require_once('../class/class_reservation.php');
    $db = new connexion();
    $con = $db->getconnexion();
    $reservation = new Reservation($con);

    $id_reservation = intval($_GET['stat']);
    if ($reservation->updateStatut($id_reservation, 'honoré')) {
        header("Location: ../../view/mes_reservations.php?success=1");
        exit();
    } else {
        echo "❌ Échec de la mise à jour du statut.";
    }
}

if (isset($_GET['sup'])) {
    $reservation->setId((int)$_GET['sup']);

    if ($reservation->delete()) {
        $msg = "✅ Réservation supprimée avec succès.";
    } else {
        $msg = "❌ Erreur lors de la suppression.";
    }
}

?>
