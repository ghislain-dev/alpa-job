<?php
require_once('../../connexion/connexion.php');
require_once('../class/class_reservation.php');

$db = new connexion();
$con = $db->getconnexion();
$reservation = new Reservation($con);

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['ajouter'])) {
        $reservation->setDescription($_POST['nom']);
        $reservation->setDate($_POST['date']);
        $reservation->setDateDebut($_POST['date_debut']);
        $reservation->setDateFin($_POST['date_fin']);
        $reservation->setIdClient($_POST['id_client']);
        $reservation->setIdSalle($_POST['salle']);

        if ($reservation->checkConflit()) {
            $msg = "❌ Ce créneau est déjà réservé. Choisissez une autre date.";
        } else {
            if ($reservation->add()) {
                $msg = "✅ Réservation effectuée avec succès.";
            } else {
                $msg = "❌ Erreur lors de l'enregistrement.";
            }
        }
    } elseif (isset($_POST['modifier'])) {
        $reservation->setId($_POST['id_reservation']);
        $reservation->setDescription($_POST['nom']);
        $reservation->setDate($_POST['date']);
        $reservation->setDateDebut($_POST['date_debut']);
        $reservation->setDateFin($_POST['date_fin']);
        $reservation->setIdClient($_POST['id_client']);
        $reservation->setIdSalle($_POST['salle']);

        if ($reservation->checkConflit()) {
            $msg = "❌ Ce créneau est déjà réservé. Choisissez une autre date.";
        } else {
            if ($reservation->update()) {
                $msg = "✅ Réservation modifiée avec succès.";
            } else {
                $msg = "❌ Erreur lors de la modification.";
            }
        }
    } elseif (isset($_POST['supprimer'])) {
        $reservation->setId($_POST['id_reservation']);
        if ($reservation->delete()) {
            $msg = "✅ Réservation supprimée avec succès.";
        } else {
            $msg = "❌ Erreur lors de la suppression.";
        }
    }
    
    // Redirection vers la page de réservation avec message
    header("Location: ../../view/reserver.php?message=" . urlencode($msg));
    exit();
}
?>
