<?php
require_once("../../connexion/connexion.php");
$db = new connexion();
$con = $db->getconnexion();

if (isset($_GET['sup_reapp'])) {
    $id_reappro = intval($_GET['sup_reapp']);

    // Vérifier si ce réapprovisionnement a été utilisé dans une vente
    $sqlVerif = "SELECT COUNT(*) FROM detail_commande WHERE id_reapprovisionnement = ?";
    $stmtVerif = $con->prepare($sqlVerif);
    $stmtVerif->execute([$id_reappro]);
    $nbVentes = $stmtVerif->fetchColumn();

    if ($nbVentes > 0) {
        // Il y a déjà eu des ventes => on désactive plutôt que supprimer
        $sqlDesactivation = "UPDATE reapprovisionnement SET statut = 'inactif' WHERE id_reapprovisionnement = ?";
        $stmt = $con->prepare($sqlDesactivation);
        $stmt->execute([$id_reappro]);

        header("Location: ../../view/index_admin.php?supprime=inactif");
        exit();
    } else {
        // Aucune vente => suppression autorisée
        $sqlSuppression = "DELETE FROM reapprovisionnement WHERE id_reapprovisionnement = ?";
        $stmt = $con->prepare($sqlSuppression);
        $stmt->execute([$id_reappro]);

        header("Location: ../../view/index_admin.php?supprime=ok");
        exit();
    }
}
?>
