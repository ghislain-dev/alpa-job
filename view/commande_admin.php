<?php
session_start();
require_once('../connexion/connexion.php');

$db = new connexion();
$con = $db->getconnexion();

// ✅ Marquer une commande comme livrée
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_commande'])) {
    $id_commande = intval($_POST['id_commande']);
    $sql = "UPDATE commande SET statut_commande = 'livrée' WHERE id_commande = ?";
    $stmt = $con->prepare($sql);
    if ($stmt->execute([$id_commande])) {
        header("Location: commande_admin.php?success=livree");
        exit;
    } else {
        echo "❌ Erreur lors de la mise à jour.";
        exit;
    }
}

// ✅ Pagination
$limite = 3;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$debut = ($page - 1) * $limite;

// ✅ Compter toutes les commandes payées (non livrées)
$sql_total = "SELECT COUNT(DISTINCT c.id_commande) AS total
              FROM commande c
              WHERE c.statut_commande = 'payée'";
$stmt_total = $con->query($sql_total);
$total_commandes = $stmt_total->fetchColumn();
$total_pages = ceil($total_commandes / $limite);

// ✅ Récupérer les commandes payées (non livrées)
$sql = "SELECT c.id_commande, c.datecommande, c.montant_total, c.statut_commande, 
               cl.nom AS nom_client, d.id_produit, d.quantite, p.nom_produit, p.image, pr.montant AS prix_unitaire 
        FROM commande c 
        JOIN client cl ON c.id_client = cl.id_client
        JOIN details_commande d ON c.id_commande = d.id_commande 
        JOIN produit p ON d.id_produit = p.id_produit 
        JOIN prix pr ON p.id_prix = pr.id_prix 
        WHERE c.statut_commande = 'payée'
        ORDER BY c.datecommande DESC
        LIMIT $limite OFFSET $debut";

$stmt = $con->query($sql);
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Regrouper par commande
$commandes = [];
foreach ($resultats as $row) {
    $id = $row['id_commande'];
    if (!isset($commandes[$id])) {
        $commandes[$id] = [
            'date' => $row['datecommande'],
            'total' => $row['montant_total'],
            'client' => $row['nom_client'],
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes payées - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/nav_admin.css">
</head>
<body>

<div class="container-fluid bg-light">
    <div class="row align-items-start">
        <?php require_once('nav_admin.php') ?>
        
        <div class="col-md-8 col-lg-9 m-5">
            <!-- ✅ Message de succès -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 'livree'): ?>
                <div class="alert alert-success text-center mt-3">
                    ✅ La commande a été marquée comme <strong>livrée</strong> avec succès.
                </div>
            <?php endif; ?>

            <h2>📦 Commandes payées (non encore livrées)</h2>

            <?php if (empty($commandes)): ?>
                <p class="text-muted">Aucune commande payée à livrer.</p>
            <?php else: ?>
                <?php foreach ($commandes as $id_commande => $info): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Commande #<?= $id_commande ?></strong> | <?= date('d/m/Y', strtotime($info['date'])) ?>
                                <br><span class="text-muted">Client : <?= htmlspecialchars($info['client']) ?></span>
                            </div>
                            <span class="badge bg-success text-uppercase"><?= htmlspecialchars($info['statut']) ?></span>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Image</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($info['produits'] as $prod): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($prod['nom']) ?></td>
                                            <td>
                                                <img src="../models/controleurs/avatar/<?= htmlspecialchars($prod['image']) ?>" width="60" height="60" style="object-fit: cover;">
                                            </td>
                                            <td><?= intval($prod['quantite']) ?></td>
                                            <td><?= number_format($prod['prix'], 2) ?> $</td>
                                            <td><?= number_format($prod['prix'] * $prod['quantite'], 2) ?> $</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="text-end mt-2">
                                <strong>Total : <?= number_format($info['total'], 2) ?> $</strong>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <form action="commande_admin.php" method="POST">
                                <input type="hidden" name="id_commande" value="<?= $id_commande ?>">
                                <button type="submit" class="btn btn-primary">
                                    📦 Marquer comme livrée
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- ✅ Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
