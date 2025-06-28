<?php
require_once("../connexion/connexion.php");
require_once("../models/class/class_produit.php");
require_once("../models/class/class_stock.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

$db = new connexion();
$con = $db->getconnexion();
$affichage = new produit($con);

// Récupérer les réapprovisionnements expirés (statut actif seulement)
$expiredQuery = "SELECT r.*, p.nom_produit, f.noms AS nom_fournisseur
                 FROM reapprovisionnement r
                 LEFT JOIN produit p ON r.id_produit = p.id_produit
                 LEFT JOIN fournisseur f ON r.id_fournisseur = f.id_fournisseur
                 WHERE r.date_exp IS NOT NULL
                   AND DATEDIFF(r.date_exp, CURDATE()) < 0
                   AND (r.statut IS NULL OR r.statut = 'actif')";
$expiredResult = $con->query($expiredQuery)->fetchAll(PDO::FETCH_ASSOC);

// Compter le total et la quantité expirée
$total_expire = count($expiredResult);
$total_quantite_expiree = 0;
foreach ($expiredResult as $exp) {
    $total_quantite_expiree += intval($exp['quantite_ajoutee']);
}

// Stock réel par produit (via vue)
$sql = "SELECT nom_produit, quantite_totale FROM vue_stock_reel";
$stmt = $con->query($sql);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Envoi d'e-mail s'il y a des expirés
if ($total_expire > 0) {
    $message = "<h3>Réapprovisionnements expirés détectés</h3><ul>";
    foreach ($expiredResult as $exp) {
        $message .= "<li><strong>{$exp['nom_produit']}</strong> (Fournisseur : {$exp['nom_fournisseur']}) — Expiré le " . date('d/m/Y', strtotime($exp['date_exp'])) . "</li>";
    }
    $message .= "</ul>";

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tonadresse@gmail.com'; // À remplacer
        $mail->Password = 'ton_mot_de_passe_application'; // À remplacer
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tonadresse@gmail.com', 'Alpa Job Notification');
        $mail->addAddress('destinataire@example.com'); // À remplacer
        $mail->isHTML(true);
        $mail->Subject = 'Alerte : Réapprovisionnements expirés';
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        // Optionnel : log l'erreur
    }
}

// Produits avec stock faible (rupture)
$produits_rupture = $con->query("
    SELECT p.id_produit, p.nom_produit, vs.quantite_totale
    FROM produit p
    JOIN vue_stock_reel vs ON p.id_produit = vs.id_produit
    WHERE vs.quantite_totale <= 20
")->fetchAll(PDO::FETCH_ASSOC);

// Tous les produits (avec leur quantité)
$liste_produits = $con->query("
    SELECT p.id_produit, p.nom_produit, vs.quantite_totale
    FROM produit p
    JOIN vue_stock_reel vs ON p.id_produit = vs.id_produit
")->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tableau de bord | Alpa Job</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
      padding-top: 20px;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .dashboard-card {
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">

    <?php include_once('nav_admin.php'); ?>

    <div class="col-md-9 col-lg-10 p-4">
      <?php if (isset($_GET['supprime'])): ?>
        <?php if ($_GET['supprime'] === 'ok'): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ Réapprovisionnement supprimé avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
          </div>
        <?php elseif ($_GET['supprime'] === 'inactif'): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            ⚠️ Ce réapprovisionnement a déjà été utilisé : il a été marqué comme <strong>inactif</strong> (non supprimé).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <h2 class="mb-4">Tableau de bord</h2>

      <div class="row g-4 p-2">
        <div class="col-md-6">
          <div class="p-3 bg-primary text-white dashboard-card">
            <h5><i class="bi bi-calendar-check"></i> Réservations</h5>
            <p class="mb-0">12 réservations en attente</p>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="p-3 bg-warning text-dark dashboard-card">
            <h5><i class="bi bi-exclamation-triangle"></i> Alerte Stock</h5>
            <p class="mb-0">
              <?= $total_expire ?> réapprovisionnement(s) expiré(s)
              <br><strong>Total : <?= $total_quantite_expiree ?> unité(s)</strong>
              <?php if ($total_expire > 0): ?>
                <button class="btn btn-sm btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modalProduitsExpires">
                  Voir détails
                </button>
              <?php endif; ?>
            </p>
          </div>
        </div>
        <div class="row p-2">
          <div class="col-md-6">
            <div class="p-3 bg-danger text-white dashboard-card">
              <h5><i class="bi bi-exclamation-circle"></i> Produits en rupture</h5>
              <p><?= count($produits_rupture) ?> produit(s) ont une quantité ≤ 20</p>
              <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalRupture">Voir plus</button>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 bg-secondary text-white dashboard-card">
              <h5><i class="bi bi-box"></i> Tous les produits</h5>
              <p><?= count($liste_produits) ?> produit(s) enregistrés</p>
              <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTousProduits">Voir plus</button>
            </div>
          </div>
        </div>
      </div>

  <div class="modal fade" id="modalTousProduits" tabindex="-1" aria-labelledby="modalTousProduitsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="modalTousProduitsLabel">📋 Liste de tous les produits</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered table-sm">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Quantité disponible</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; foreach ($liste_produits as $prod): ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($prod['nom_produit']) ?></td>
                  <td><?= $prod['quantite_totale'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  








      <div class="card mt-5 shadow-sm">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0">📊 Niveau de stock par produit</h5>
        </div>
        <div class="card-body">
          <canvas id="stockChart" height="100"></canvas>
        </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        const labels = <?= json_encode(array_column($produits, 'nom_produit')) ?>;
        const data = <?= json_encode(array_column($produits, 'quantite_totale')) ?>;

        new Chart(document.getElementById('stockChart'), {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Quantité en stock',
              data: data,
              backgroundColor: 'rgba(13, 110, 253, 0.7)',
              borderColor: 'rgba(13, 110, 253, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                title: {
                  display: true,
                  text: 'Quantité'
                }
              }
            }
          }
        });
      </script>

      <div class="mt-5">
        <h5>Activités récentes</h5>
        <ul class="list-group">
          <li class="list-group-item">🔁 Mise à jour du stock de "Chaise plastique"</li>
          <li class="list-group-item">📦 Nouveau produit ajouté : "Tente 6m x 6m"</li>
          <li class="list-group-item">📅 Réservation confirmée pour le 24 juin</li>
        </ul>
      </div>

      <?php if ($total_expire > 0): ?>
        <div class="modal fade" id="modalProduitsExpires" tabindex="-1" aria-labelledby="modalProduitsExpiresLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalProduitsExpiresLabel">Réapprovisionnements expirés</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
              </div>
              <div class="modal-body">
                <table class="table table-bordered align-middle table-sm">
                  <thead class="table-danger">
                    <tr>
                      <th>#</th>
                      <th>Produit</th>
                      <th>Fournisseur</th>
                      <th>Date d’expiration</th>
                      <th>Quantité</th>
                      <th>Supprimer</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1; foreach ($expiredResult as $exp): ?>
                      <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($exp['nom_produit']) ?></td>
                        <td><?= htmlspecialchars($exp['nom_fournisseur']) ?></td>
                        <td><?= date('d/m/Y', strtotime($exp['date_exp'])) ?></td>
                        <td><?= htmlspecialchars($exp['quantite_ajoutee']) ?></td>
                        <td>
                          <a href="../models/controleurs/controls_produit.php?sup_reapp=<?= $exp['id_reapprovisionnement'] ?>"
                             class="btn btn-sm btn-danger"
                             onclick="return confirm('Supprimer ce réapprovisionnement expiré ?')">
                            Supprimer
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
