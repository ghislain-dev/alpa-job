<?php
session_start();
require_once("../connexion/connexion.php");

$id_client = 1; // à remplacer par la vraie session client

$db = new connexion();
$con = $db->getconnexion();

// Récupérer les commandes avec détails
$sql = "SELECT c.id_commande, c.datecommande, c.montant_total, c.statut_commande, 
               d.id_produit, d.quantite, p.nom_produit, p.image, pr.montant AS prix_unitaire 
        FROM commande c 
        JOIN details_commande d ON c.id_commande = d.id_commande 
        JOIN produit p ON d.id_produit = p.id_produit 
        JOIN prix pr ON p.id_prix = pr.id_prix 
        WHERE c.id_client = ? 
        ORDER BY c.datecommande DESC";

$stmt = $con->prepare($sql);
$stmt->execute([$id_client]);
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper les commandes par statut
$groupes = [];
foreach ($resultats as $row) {
    $statut = strtolower($row['statut_commande']);
    $id = $row['id_commande'];
    if (!isset($groupes[$statut][$id])) {
        $groupes[$statut][$id] = [
            'date' => $row['datecommande'],
            'total' => $row['montant_total'],
            'statut' => $statut,
            'produits' => []
        ];
    }
    $groupes[$statut][$id]['produits'][] = [
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
    <title>Mes commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>📦 Mes commandes</h2>

    <?php if (empty($groupes)): ?>
        <p>Aucune commande enregistrée.</p>
    <?php else: ?>
        <?php foreach ($groupes as $statut => $commandes): ?>
            <h4 class="mt-4 text-<?= $statut === 'payée' ? 'success' : 'warning' ?>">
                <?= ucfirst($statut) ?><?= $statut === 'payée' ? 's' : 's en attente' ?>
            </h4>
            <?php foreach ($commandes as $id_commande => $info): ?>
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        Commande #<?= $id_commande ?> - <?= date('d/m/Y', strtotime($info['date'])) ?>
                        <span class="float-end badge bg-<?= $info['statut'] === 'payée' ? 'success' : 'warning' ?>">
                            <?= ucfirst($info['statut']) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
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
                                    <td><img src="../models/controleurs/avatar/<?= htmlspecialchars($prod['image']) ?>" width="60" height="60" style="object-fit: cover;"></td>
                                    <td><?= $prod['quantite'] ?></td>
                                    <td><?= number_format($prod['prix'], 2) ?> $</td>
                                    <td><?= number_format($prod['prix'] * $prod['quantite'], 2) ?> $</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <strong>Total : <?= number_format($info['total'], 2) ?> $</strong>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <?php if ($info['statut'] !== 'payée'): ?>
                            <form action="payer_commande.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_commande" value="<?= $id_commande ?>">
                                <button type="submit" class="btn btn-success">💳 Payer</button>
                            </form>
                        <?php else: ?>
                            <span class="text-success fw-bold">Commande déjà payée</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
