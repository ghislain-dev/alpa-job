<?php
require_once("../connexion/connexion.php");
$db = new connexion();
$con = $db->getconnexion();

// Config pagination
$parPage = 5;

// Récupérer page actuelle
$page_cmd = isset($_GET['page_cmd']) ? (int)$_GET['page_cmd'] : 1;
$page_res = isset($_GET['page_res']) ? (int)$_GET['page_res'] : 1;

// Recherche
$search_commande = isset($_GET['search_commande']) ? trim($_GET['search_commande']) : '';
$search_reservation = isset($_GET['search_reservation']) ? trim($_GET['search_reservation']) : '';

// Période
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : '';
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : '';

// ---------- COMMANDES ----------
$sql_commandes = "
    SELECT p.id_paiement, p.montant, p.devise, p.date, c.id_commande, cl.nom AS nom_client
    FROM paiement p
    LEFT JOIN commande c ON c.id_commande = p.id_commande
    LEFT JOIN client cl ON cl.id_client = c.id_client
    WHERE 1
";
$params = [];

if ($date_debut && $date_fin) {
    $sql_commandes .= " AND p.date BETWEEN :date_debut AND :date_fin ";
    $params[':date_debut'] = $date_debut;
    $params[':date_fin'] = $date_fin;
}
if ($search_commande) {
    $sql_commandes .= " AND cl.nom LIKE :search_commande ";
    $params[':search_commande'] = '%'.$search_commande.'%';
}

$sql_commandes_count = "SELECT COUNT(*) FROM ($sql_commandes) as sub";
$stmt = $con->prepare($sql_commandes_count);
$stmt->execute($params);
$total_cmd = $stmt->fetchColumn();

$start_cmd = ($page_cmd - 1) * $parPage;

$sql_commandes .= " ORDER BY p.date DESC LIMIT :start, :perpage";
$stmt = $con->prepare($sql_commandes);
foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
$stmt->bindValue(':start', $start_cmd, PDO::PARAM_INT);
$stmt->bindValue(':perpage', $parPage, PDO::PARAM_INT);
$stmt->execute();
$paiements_commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------- RESERVATIONS ----------
$sql_reservations = "
    SELECT pr.id_paiement, pr.montant, pr.date_paiement, r.id_reservation, cl.nom AS nom_client
    FROM paiement_reservation pr
    LEFT JOIN reservation r ON r.id_reservation = pr.id_reservation
    LEFT JOIN client cl ON cl.id_client = r.id_client
    WHERE 1
";
$params_res = [];

if ($date_debut && $date_fin) {
    $sql_reservations .= " AND pr.date_paiement BETWEEN :date_debut AND :date_fin ";
    $params_res[':date_debut'] = $date_debut;
    $params_res[':date_fin'] = $date_fin;
}
if ($search_reservation) {
    $sql_reservations .= " AND cl.nom LIKE :search_reservation ";
    $params_res[':search_reservation'] = '%'.$search_reservation.'%';
}

$sql_reservations_count = "SELECT COUNT(*) FROM ($sql_reservations) as sub";
$stmt = $con->prepare($sql_reservations_count);
$stmt->execute($params_res);
$total_res = $stmt->fetchColumn();

$start_res = ($page_res - 1) * $parPage;

$sql_reservations .= " ORDER BY pr.date_paiement DESC LIMIT :start, :perpage";
$stmt = $con->prepare($sql_reservations);
foreach ($params_res as $k => $v) { $stmt->bindValue($k, $v); }
$stmt->bindValue(':start', $start_res, PDO::PARAM_INT);
$stmt->bindValue(':perpage', $parPage, PDO::PARAM_INT);
$stmt->execute();
$paiements_reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport Comptable</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="css/nav_admin.css">
<style>
  @media print {
    .no-print { display: none; }
  }
</style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row ">
        <?php   include_once('nav_comptable.php') ?>
            <div class="col-md-10 mt-4">
                
            <h2 class="mb-4 text-center">📊 Rapport Comptable</h2>

            <div class="card mb-4">
                <div class="card-header bg-info text-white">🔍 Filtrer par période</div>
                <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-4">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($date_debut) ?>">
                    </div>
                    <div class="col-md-4">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($date_fin) ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-info me-2">Filtrer</button>
                    <button type="button" onclick="window.print()" class="btn btn-secondary">Imprimer le rapport</button>
                    </div>
                </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">💰 Paiements des commandes</div>
                <div class="card-body">
                    <form method="get" class="mb-3 d-flex">
                        <input type="hidden" name="date_debut" value="<?= htmlspecialchars($date_debut) ?>">
                        <input type="hidden" name="date_fin" value="<?= htmlspecialchars($date_fin) ?>">
                        <input type="text" name="search_commande" value="<?= htmlspecialchars($search_commande) ?>" class="form-control me-2" placeholder="Rechercher client...">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </form>

                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ID Commande</th>
                        <th>Nom du client</th>
                        <th>Montant</th>
                        <th>Devise</th>
                        <th>Date</th>
                        <th class="no-print">Reçu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($paiements_commandes): 
                    foreach($paiements_commandes as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_paiement']) ?></td>
                        <td><?= htmlspecialchars($row['id_commande']) ?></td>
                        <td><?= htmlspecialchars($row['nom_client']) ?></td>
                        <td><?= htmlspecialchars($row['montant']) ?></td>
                        <td><?= htmlspecialchars($row['devise']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td class="no-print">
                        <a href="imprimer_recu.php?id_paiement=<?= $row['id_paiement'] ?>&type=commande" class="btn btn-sm btn-outline-primary">🖨 Reçu</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center">Aucun paiement trouvé</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php
                    $total_pages_cmd = ceil($total_cmd / $parPage);
                    if($total_pages_cmd > 1):
                    ?>
                    <nav>
                    <ul class="pagination justify-content-center">
                        <?php for($i=1;$i<=$total_pages_cmd;$i++): ?>
                        <li class="page-item <?= ($i == $page_cmd)?'active':'' ?>">
                            <a class="page-link" href="?page_cmd=<?= $i ?>&search_commande=<?= urlencode($search_commande) ?>&date_debut=<?= urlencode($date_debut) ?>&date_fin=<?= urlencode($date_fin) ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                    </nav>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">🏠 Paiements des réservations</div>
                <div class="card-body">
                    <form method="get" class="mb-3 d-flex">
                        <input type="hidden" name="date_debut" value="<?= htmlspecialchars($date_debut) ?>">
                        <input type="hidden" name="date_fin" value="<?= htmlspecialchars($date_fin) ?>">
                        <input type="text" name="search_reservation" value="<?= htmlspecialchars($search_reservation) ?>" class="form-control me-2" placeholder="Rechercher client...">
                        <button type="submit" class="btn btn-success">Rechercher</button>
                    </form>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ID Réservation</th>
                        <th>Nom du client</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th class="no-print">Reçu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($paiements_reservations): 
                    foreach($paiements_reservations as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_paiement']) ?></td>
                        <td><?= htmlspecialchars($row['id_reservation']) ?></td>
                        <td><?= htmlspecialchars($row['nom_client']) ?></td>
                        <td><?= htmlspecialchars($row['montant']) ?></td>
                        <td><?= htmlspecialchars($row['date_paiement']) ?></td>
                        <td class="no-print">
                        <a href="imprimer_recu.php?id_paiement=<?= $row['id_paiement'] ?>" class="btn btn-sm btn-outline-success">🖨 Reçu</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center">Aucun paiement trouvé</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php
                    $total_pages_res = ceil($total_res / $parPage);
                    if($total_pages_res > 1):
                    ?>
                    <nav>
                    <ul class="pagination justify-content-center">
                        <?php for($i=1;$i<=$total_pages_res;$i++): ?>
                        <li class="page-item <?= ($i == $page_res)?'active':'' ?>">
                            <a class="page-link" href="?page_res=<?= $i ?>&search_reservation=<?= urlencode($search_reservation) ?>&date_debut=<?= urlencode($date_debut) ?>&date_fin=<?= urlencode($date_fin) ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                    </nav>
                <?php endif; ?>

                </div>
            </div>
            </div>
    </div>
</div>
</body>
</html>
