<?php
require_once("../connexion/connexion.php");

$db = new connexion();
$con = $db->getconnexion();

// Recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagination
$page_cmd = isset($_GET['page_cmd']) ? (int)$_GET['page_cmd'] : 1;
$page_res = isset($_GET['page_res']) ? (int)$_GET['page_res'] : 1;
$limit = 10;
$offset_cmd = ($page_cmd - 1) * $limit;
$offset_res = ($page_res - 1) * $limit;

// --- Paiements des commandes
$sql_cmd = "
    SELECT p.id_paiement, p.montant, p.devise, p.date, c.id_commande, cl.nom AS nom_client
    FROM paiement p
    LEFT JOIN commande c ON c.id_commande = p.id_commande
    LEFT JOIN client cl ON cl.id_client = c.id_client
    WHERE cl.nom LIKE :search
    ORDER BY p.date DESC
    LIMIT :limit OFFSET :offset
";
$stmt_cmd = $con->prepare($sql_cmd);
$stmt_cmd->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt_cmd->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt_cmd->bindValue(':offset', $offset_cmd, PDO::PARAM_INT);
$stmt_cmd->execute();
$paiements_cmd = $stmt_cmd->fetchAll(PDO::FETCH_ASSOC);

// Compter total pour pagination commandes
$count_cmd = $con->prepare("
    SELECT COUNT(*) FROM paiement p
    LEFT JOIN commande c ON c.id_commande = p.id_commande
    LEFT JOIN client cl ON cl.id_client = c.id_client
    WHERE cl.nom LIKE :search
");
$count_cmd->bindValue(':search', "%$search%", PDO::PARAM_STR);
$count_cmd->execute();
$total_cmd = $count_cmd->fetchColumn();
$total_pages_cmd = ceil($total_cmd / $limit);

// --- Paiements des réservations
$sql_res = "
    SELECT pr.id_paiement, pr.montant, pr.date_paiement, r.id_reservation, cl.nom AS nom_client
    FROM paiement_reservation pr
    LEFT JOIN reservation r ON r.id_reservation = pr.id_reservation
    LEFT JOIN client cl ON cl.id_client = r.id_client
    WHERE cl.nom LIKE :search
    ORDER BY pr.date_paiement DESC
    LIMIT :limit OFFSET :offset
";
$stmt_res = $con->prepare($sql_res);
$stmt_res->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt_res->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt_res->bindValue(':offset', $offset_res, PDO::PARAM_INT);
$stmt_res->execute();
$paiements_res = $stmt_res->fetchAll(PDO::FETCH_ASSOC);

// Compter total pour pagination réservations
$count_res = $con->prepare("
    SELECT COUNT(*) FROM paiement_reservation pr
    LEFT JOIN reservation r ON r.id_reservation = pr.id_reservation
    LEFT JOIN client cl ON cl.id_client = r.id_client
    WHERE cl.nom LIKE :search
");
$count_res->bindValue(':search', "%$search%", PDO::PARAM_STR);
$count_res->execute();
$total_res = $count_res->fetchColumn();
$total_pages_res = ceil($total_res / $limit);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Page Comptable - Paiements</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/nav_admin.css">
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row ">
            <?php include('nav_comptable.php') ?>
        <div class="col-md-10 justify-content-center mt-5 align-items-center">

        <h2 class="mb-4 text-center">🔐 Tableau de bord - Comptable</h2>

        <form method="get" class="mb-3 d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par nom du client" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>

        <!-- Paiements des commandes -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">💰 Paiements des commandes</div>
            <div class="card-body table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>ID Commande</th>
                    <th>Montant</th>
                    <th>Devise</th>
                    <th>Date paiement</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($paiements_cmd): 
                    foreach($paiements_cmd as $row): ?>
                    <tr>
                    <td><?= htmlspecialchars($row['id_paiement']) ?></td>
                    <td><?= htmlspecialchars($row['nom_client']) ?></td>
                    <td><?= htmlspecialchars($row['id_commande']) ?></td>
                    <td><?= htmlspecialchars($row['montant']) ?></td>
                    <td><?= htmlspecialchars($row['devise']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center">Aucun paiement trouvé</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <!-- Pagination commandes -->
            <nav>
                <ul class="pagination">
                <?php for ($i=1; $i <= $total_pages_cmd; $i++): ?>
                    <li class="page-item <?= $i==$page_cmd ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page_cmd=<?= $i ?>&page_res=<?= $page_res ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                </ul>
            </nav>
            </div>
        </div>

        <!-- Paiements des réservations -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">🏠 Paiements des réservations</div>
            <div class="card-body table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>ID Réservation</th>
                    <th>Montant</th>
                    <th>Date paiement</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($paiements_res): 
                    foreach($paiements_res as $row): ?>
                    <tr>
                    <td><?= htmlspecialchars($row['id_paiement']) ?></td>
                    <td><?= htmlspecialchars($row['nom_client']) ?></td>
                    <td><?= htmlspecialchars($row['id_reservation']) ?></td>
                    <td><?= htmlspecialchars($row['montant']) ?></td>
                    <td><?= htmlspecialchars($row['date_paiement']) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center">Aucun paiement trouvé</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <!-- Pagination réservations -->
            <nav>
                <ul class="pagination">
                <?php for ($i=1; $i <= $total_pages_res; $i++): ?>
                    <li class="page-item <?= $i==$page_res ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page_cmd=<?= $page_cmd ?>&page_res=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                </ul>
            </nav>
            </div>
        </div>
        </div>
    </div> 
</div> 
</body>
</html>
<?php
$con = null;
?>
