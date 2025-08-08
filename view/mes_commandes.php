<?php
session_start();

// Vérifier si le client est connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
require_once("../connexion/connexion.php");

$id_client = $_SESSION['id'];
$db = new connexion();
$con = $db->getconnexion();

// Pagination
$commandesParPage = 5;
$pageActuelle = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($pageActuelle - 1) * $commandesParPage;

// Compter les commandes totales
$sqlTotal = "SELECT COUNT(DISTINCT c.id_commande) AS total 
             FROM commande c
             WHERE c.id_client = ? 
               AND c.statut_commande NOT IN ('annulée', 'rejetée')";
$stmtTotal = $con->prepare($sqlTotal);
$stmtTotal->execute([$id_client]);
$totalCommandes = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalCommandes / $commandesParPage);

// Requête paginée
$sql = "SELECT c.id_commande, c.datecommande, c.montant_total, c.statut_commande, 
               d.id_produit, d.quantite, p.nom_produit, p.image, pr.montant AS prix_unitaire 
        FROM commande c 
        JOIN details_commande d ON c.id_commande = d.id_commande 
        JOIN produit p ON d.id_produit = p.id_produit 
        JOIN prix pr ON p.id_prix = pr.id_prix 
        WHERE c.id_client = ? 
          AND c.statut_commande NOT IN ('annulée', 'rejetée') 
        ORDER BY c.datecommande DESC
        LIMIT ? OFFSET ?";

$stmt = $con->prepare($sql);
$stmt->bindValue(1, $id_client, PDO::PARAM_INT);
$stmt->bindValue(2, (int)$commandesParPage, PDO::PARAM_INT);
$stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Regrouper les commandes
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
    <main>
        <?php
            include_once("navbar_clientt.php");
        ?>
    </main>
<div class="container mt-4">
    <h2>📦 Mes commandes</h2>

    <?php if (empty($groupes)): ?>
        <p>Aucune commande enregistrée.</p>
    <?php else: ?>
        <?php foreach ($groupes as $statut => $commandes): ?>
            <h4 class="mt-4 text-<?= $statut === 'payée' ? 'success' : 'warning' ?>">
                <?= ucfirst($statut) ?><?= $statut === 'payée' ? 's' : 's en attente' ?>
            </h4>

            <div class="row"> <!-- ✅ DEBUT LIGNE -->
                <?php foreach ($commandes as $id_commande => $info): ?>
                    <div class="col-md-6"> <!-- ✅ Deux colonnes par ligne -->

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
                                <?php if ($info['statut'] !== 'payée' && $info['statut'] !== 'livrée'): ?>
                                    <form action="payer_commande.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_commande" value="<?= $id_commande ?>">
                                        <button type="submit" class="btn btn-success" name="simulerr">💳 Payer</button>
                                    </form>

                                    <form action="../models/controleurs/controls_annuler_c.php" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer l\'annulation de cette commande ?');">
                                        <input type="hidden" name="id_commande" value="<?= $id_commande ?>">
                                        <button type="submit" class="btn btn-danger ms-2">🗑️ Annuler</button>
                                    </form>
                                <?php elseif ($info['statut'] === 'payée' || $info['statut'] === 'livrée'): ?>
                                    <span class="text-success fw-bold">
                                        Commande déjà <?= $info['statut'] === 'payée' ? 'payée' : 'livrée' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div> <!-- fin col-md-6 -->
                <?php endforeach; ?>
            </div> <!-- ✅ FIN LIGNE -->
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i === $pageActuelle) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
