<?php 
require_once('../connexion/connexion.php');

$db = new connexion();
$con = $db->getconnexion();
$requete = $con->query("SELECT * FROM vue_stock_fifo");
$stocks = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Stock FIFO | Alpa Job</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/nav_admin.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .table th, .table td {
      vertical-align: middle;
      text-align: center;
    }
    .img-thumbnail {
      object-fit: cover;
      width: 60px;
      height: 60px;
    }
  </style>
</head>
<body>
<div class="container-fluid bg-light ">
<div class="row align-items-center">
    <?php require_once('nav_admin.php') ?>
    <div class="col-md-8 col-lg-9 m-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
        <h4 class="mb-0">📦 Vue FIFO du Stock</h4>
        </div>
        <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Produit</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Entrées</th>
                <th>Expirés</th>
                <th>Valides</th>
                <th>Sorties</th>
                <th>Stock Restant</th>
                <th>Première entrée</th>
                <th>Dernière entrée</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; foreach ($stocks as $stock): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><img src="../models/controleurs/avatar/<?= $stock['image'] ?>" class="img-thumbnail"></td>
                <td><?= htmlspecialchars($stock['nom_produit']) ?></td>
                <td><?= htmlspecialchars($stock['nom_categorie']) ?></td>
                <td><?= number_format($stock['prix'], 2) ?> $</td>
                <td><?= $stock['total_entree'] ?></td>
                <td class="text-danger fw-bold"><?= $stock['stock_expire'] ?></td>
                <td class="text-success fw-bold"><?= $stock['stock_valide'] ?></td>
                <td><?= $stock['total_sortie'] ?></td>
                <td class="fw-bold <?= $stock['stock_restant'] <= 0 ? 'text-danger' : 'text-primary' ?>">
                <?= $stock['stock_restant'] ?>
                </td>
                <td><?= date('d/m/Y', strtotime($stock['premiere_entree'])) ?></td>
                <td><?= date('d/m/Y', strtotime($stock['derniere_entree'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
