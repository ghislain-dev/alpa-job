<?php
session_start();
include_once('../../connexion/connexion.php');

$db = new connexion();
$con = $db->getconnexion();

// ID client (à adapter selon votre système d'authentification)
$id_client = $_SESSION['id_client'] ?? 1;

// ✅ Marquer une commande comme livrée
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_commande'])) {
    $id_commande = intval($_POST['id_commande']);
    $sql = "UPDATE commande SET statut_commande = 'livrée' WHERE id_commande = ?";
    $stmt = $con->prepare($sql);
    if ($stmt->execute([$id_commande])) {
        header("Location: commandes_payees.php?success=livree");
        exit;
    } else {
        echo "❌ Erreur lors de la mise à jour.";
    }
    exit;
}

// ✅ Pagination
$limite = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$debut = ($page - 1) * $limite;

// Compter les commandes payées
$sql_total = "SELECT COUNT(DISTINCT c.id_commande) AS total 
              FROM commande c 
              WHERE c.id_client = ? AND c.statut_commande = 'payée'";
$stmt_total = $con->prepare($sql_total);
$stmt_total->execute([$id_client]);
$total_commandes = $stmt_total->fetchColumn();
$total_pages = ceil($total_commandes / $limite);

// ✅ Récupérer les commandes payées avec détails
$sql = "SELECT c.id_commande, c.datecommande, c.montant_total, c.statut_commande, 
               d.id_produit, d.quantite, p.nom_produit, p.image, pr.montant AS prix_unitaire 
        FROM commande c 
        JOIN details_commande d ON c.id_commande = d.id_commande 
        JOIN produit p ON d.id_produit = p.id_produit 
        JOIN prix pr ON p.id_prix = pr.id_prix 
        WHERE c.id_client = ? AND c.statut_commande = 'payée'
        ORDER BY c.datecommande DESC
        LIMIT $limite OFFSET $debut";
$stmt = $con->prepare($sql);
$stmt->execute([$id_client]);
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Regrouper par commande
$commandes = [];
foreach ($resultats as $row) {
    $id = $row['id_commande'];
    if (!isset($commandes[$id])) {
        $commandes[$id] = [
            'date' => $row['datecommande'],
            'total' => $row['montant_total'],
            'statut' => $row['statut_commande'],
            'produits' => []
        ];
    }
    $commandes[$id]['produits'][] = [
        'nom' => $row['nom_produit'],
        'quantite' => $row['quantite'],
        'prix' => $row['prix_unitaire'],
        'image' => $row['image']
    ];
}
?>
