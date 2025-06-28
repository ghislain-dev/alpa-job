<?php
session_start();
require_once('../connexion/connexion.php');
require_once('../models/class/class_reservation.php');

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$db = new connexion();
$con = $db->getconnexion();
$reservation = new reservation($con);

$id_client = $_SESSION['id'] ?? null; // Récupérer l'id du client connecté
if (!$id_client) {
    die("Client non identifié.");
}

$limit = 10;
$pageParamPrefix = "page_"; // Pour différencier page pour chaque statut
$statuts = ['en cours', 'payée', 'honoré'];

// Récupérer la page actuelle pour chaque statut (ex: page_en_cours, page_payee, page_honore)
$pagesData = [];
foreach ($statuts as $statut) {
    $pageVar = $pageParamPrefix . str_replace(' ', '_', $statut);
    $page = isset($_GET[$pageVar]) ? max(1, (int)$_GET[$pageVar]) : 1;
    $offset = ($page - 1) * $limit;

    // Méthode à créer dans ta classe Reservation
    $total = $reservation->count_reservations_by_client_and_statut($id_client, $statut);
    $pages = ceil($total / $limit);

    $reservations = $reservation->get_reservations_by_client_and_statut_paginated($id_client, $statut, $limit, $offset);

    $pagesData[$statut] = [
        'page' => $page,
        'offset' => $offset,
        'total' => $total,
        'pages' => $pages,
        'reservations' => $reservations,
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Mes Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include('navbar_client.php'); ?>

<div class="container mt-5">
    <h3 class="text-center mb-4">🗓️ Mes Réservations par statut</h3>

    <?php foreach ($statuts as $statut): 
        $data = $pagesData[$statut];
        $reservations = $data['reservations'];
        $page = $data['page'];
        $pages = $data['pages'];
        $offset = $data['offset'];
    ?>
    <h4 class="mt-5 text-capitalize">Statut : <?= htmlspecialchars($statut) ?></h4>

    <?php if (empty($reservations)): ?>
        <div class="alert alert-warning">Aucune réservation « <?= htmlspecialchars($statut) ?> » trouvée.</div>
    <?php else: ?>
        <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Salle</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                    <th>Payer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $index => $res): ?>
                    <tr>
                        <td><?= $offset + $index + 1 ?></td>
                        <td><?= htmlspecialchars($res['nom_salle']) ?></td>
                        <td><?= htmlspecialchars($res['description']) ?></td>
                        <td><?= htmlspecialchars($res['date']) ?></td>
                        <td><?= htmlspecialchars($res['date_debut']) ?></td>
                        <td><?= htmlspecialchars($res['date_fin']) ?></td>
                        <td>
                            <button
                                class="btn btn-sm btn-info btn-edit"
                                data-id="<?= $res['id_reservation'] ?>"
                                data-description="<?= htmlspecialchars($res['description']) ?>"
                                data-date="<?= $res['date'] ?>"
                                data-debut="<?= $res['date_debut'] ?>"
                                data-fin="<?= $res['date_fin'] ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                            >✏️ Modifier</button>
                        </td>
                        <td>
                            <a href="../models/controleurs/controls_reservation.php?sup=<?= $res['id_reservation'] ?>"
                               onclick="return confirm('Supprimer cette réservation ?')"
                               class="btn btn-sm btn-danger">🗑️ Supprimer</a>
                        </td>
                       
                        <td>
                            <form method="post" action="../models/controleurs/controls_reservation.php" onsubmit="return confirm('Confirmez-vous le paiement de cette réservation ?');" style="display:inline;">
                                <input type="hidden" name="payer" value="<?= htmlspecialchars($res['id_reservation']) ?>">
                                <button type="submit" class="btn btn-sm btn-success">💳 Payer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <!-- Pagination spécifique au statut -->
        <nav aria-label="Pagination pour <?= htmlspecialchars($statut) ?>">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= $pageParamPrefix . str_replace(' ', '_', $statut) ?>=<?= $page - 1 ?>">Précédent</a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= $pageParamPrefix . str_replace(' ', '_', $statut) ?>=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= $pageParamPrefix . str_replace(' ', '_', $statut) ?>=<?= $page + 1 ?>">Suivant</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
    <?php endforeach; ?>
</div>

<!-- Modale de modification (identique à ta version) -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../models/controleurs/controls_reservation.php" method="post" class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalEditLabel">Modifier Réservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_reservation" id="edit-id">
                <div class="mb-2">
                    <label>Description</label>
                    <input type="text" name="description" id="edit-description" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Date</label>
                    <input type="date" name="date" id="edit-date" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Date & heure début</label>
                    <input type="datetime-local" name="date_debut" id="edit-date-debut" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Date & heure fin</label>
                    <input type="datetime-local" name="date_fin" id="edit-date-fin" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="modifier" class="btn btn-success">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('edit-id').value = button.dataset.id;
            document.getElementById('edit-description').value = button.dataset.description;
            document.getElementById('edit-date').value = button.dataset.date;
            document.getElementById('edit-date-debut').value = button.dataset.debut;
            document.getElementById('edit-date-fin').value = button.dataset.fin;
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
