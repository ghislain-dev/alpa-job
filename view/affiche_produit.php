<?php
session_start();
if (isset($_GET['vider'])) {
    unset($_SESSION['panier']); // Supprime le panier de la session
    header("Location: ".$_SERVER['PHP_SELF']); // Recharge la page pour actualiser
    exit;
}

require_once("../connexion/connexion.php");

$db = new connexion();
$con = $db->getconnexion();

// Initialisation du panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Traitement ajout panier
if (isset($_POST['ajouter'])) {
    $id_produit = $_POST['id_produit'];
    $quantite = $_POST['quantite'];
    
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit] += $quantite;
    } else {
        $_SESSION['panier'][$id_produit] = $quantite;
    }
}

// Récupération des produits avec prix
$sql = "SELECT id_produit, nom_produit, image, prix FROM vue_stock_fifo WHERE stock_valide > 0";

$stmt = $con->prepare($sql);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits avec panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main>
    <?php 
        include_once("navbar_clientt.php");
    ?>
</main>
<button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#panierModal">
  🛒 Voir le panier
</button>
<?php if (isset($_SESSION['erreur_commande'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['erreur_commande'] ?>
        <?php unset($_SESSION['erreur_commande']); ?>
    </div>
<?php endif; ?>

<div class="container mt-4">
    <h2>Liste des Produits</h2>

    <div class="row">
        <?php foreach ($produits as $prod): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <img src="../models/controleurs/avatar/<?= htmlspecialchars($prod['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($prod['nom_produit']) ?></h5>
                        <p class="card-text">Prix : <?= number_format($prod['prix'], 2) ?> $</p>
                        <form method="POST">
                            <input type="hidden" name="id_produit" value="<?= $prod['id_produit'] ?>">
                            <div class="input-group">
                                <input type="number" name="quantite" min="1" value="1" class="form-control" required>
                                <button type="submit" name="ajouter" class="btn btn-primary">Ajouter au panier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 class="mt-5">🛒 Panier</h3>
    <?php if (!empty($_SESSION['panier'])): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($_SESSION['panier'] as $id_produit => $qte):
                    $stmt = $con->prepare("SELECT nom_produit, prix.montant FROM produit JOIN prix ON produit.id_prix = prix.id_prix WHERE id_produit = ?");
                    $stmt->execute([$id_produit]);
                    $prod = $stmt->fetch();
                    $sous_total = $qte * $prod['montant'];
                    $total += $sous_total;
                ?>
                <tr>
                    <td><?= htmlspecialchars($prod['nom_produit']) ?></td>
                    <td><?= $qte ?></td>
                    <td><?= number_format($prod['montant'], 2) ?> $</td>
                    <td><?= number_format($sous_total, 2) ?> $</td>
                </tr>
                <?php endforeach; ?>
                <tr class="table-success">
                    <th colspan="3">Total général</th>
                    <th><?= number_format($total, 2) ?> $</th>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun produit dans le panier.</p>
    <?php endif; ?>
</div>
<!-- Modal Panier -->
<!-- Modal Panier -->
<div class="modal fade" id="panierModal" tabindex="-1" aria-labelledby="panierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="../models/controleurs/controls_commande.php"> <!-- Form ouvert ici -->
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="panierModalLabel">🛒 Contenu du panier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <?php if (!empty($_SESSION['panier'])): ?>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $total = 0;
              foreach ($_SESSION['panier'] as $id_produit => $qte):
                  $stmt = $con->prepare("SELECT nom_produit, prix.montant FROM produit JOIN prix ON produit.id_prix = prix.id_prix WHERE id_produit = ?");
                  $stmt->execute([$id_produit]);
                  $prod = $stmt->fetch();
                  $sous_total = $qte * $prod['montant'];
                  $total += $sous_total;
              ?>
              <tr>
                <td><?= htmlspecialchars($prod['nom_produit']) ?></td>
                <td><?= $qte ?></td>
                <td><?= number_format($prod['montant'], 2) ?> $</td>
                <td><?= number_format($sous_total, 2) ?> $</td>
              </tr>
              <?php endforeach; ?>
              <tr class="table-success">
                <th colspan="3">Total général</th>
                <th><?= number_format($total, 2) ?> $</th>
              </tr>
            </tbody>
          </table>
        <?php else: ?>
          <p>Aucun produit dans le panier.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <a href="?vider=1" class="btn btn-danger">Vider le panier</a>
        <button type="submit" name="commander" class="btn btn-success">Passer la commande</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
      </form> <!-- Form fermé ici -->
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
