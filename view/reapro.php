<?php
    require_once("../connexion/connexion.php");
    require_once('../models/class/classe_reapro.php');

    $db = new connexion();
    $con = $db->getconnexion();
    $reappro = new Reapprovisionnement($con);
    $reappros = $reappro->get_all_reapprovisionnements();
    $produits = $reappro->get_produit();
    $fournisseurs = $reappro->get_fournisseur();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Liste des Réapprovisionnements</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container mt-4">
  <h2>Liste des Réapprovisionnements</h2>

  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalReappro">Ajouter un réapprovisionnement</button>

  <table class="table table-bordered table-striped mt-3 align-middle">
    <thead class="table-primary">
      <tr>
        <th>#</th>
        <th>Produit</th>
        <th>Fournisseur</th>
        <th>Quantité ajoutée</th>
        <th>Date quantité</th>
        <th>Date d'expiration</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($reappros) > 0): ?>
        <?php $i = 1; foreach ($reappros as $r): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($r['nom_produit']) ?></td>
            <td><?= htmlspecialchars($r['nom_fournisseur']) ?></td>
            <td><?= htmlspecialchars($r['quantite_ajoutee']) ?></td>
            <td><?= htmlspecialchars($r['date_entre']) ?></td>
            <td><?= htmlspecialchars($r['date_exp']) ?></td>
            
            <td>
              <button 
                class="btn btn-sm btn-warning btnEdit" 
                data-id="<?= $r['id_reapprovisionnement'] ?>"
                data-quantite="<?= $r['quantite_ajoutee'] ?>"
                
                data-dateexp="<?= $r['date_exp'] ?>"
                data-produit="<?= $r['nom_produit'] ?>"
                data-idproduit="<?= $r['id_produit'] ?>"
                data-fournisseur="<?= $r['nom_fournisseur'] ?>"
                data-idfournisseur="<?= $r['id_fournisseur'] ?? '' ?>"
                data-bs-toggle="modal" data-bs-target="#modalReappro"
              >Modifier</button>
              <a href="../controllers/controls_reapprovisionnement.php?sup=<?= $r['id_reapprovisionnement'] ?>" 
                 onclick="return confirm('Voulez-vous vraiment supprimer ce réapprovisionnement ?');"
                 class="btn btn-sm btn-danger"
              >Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center">Aucun réapprovisionnement trouvé.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="btn btn-secondary mt-3">Retour au tableau de bord</a>
</div>

<!-- Modal ajout / modification -->
<div class="modal fade" id="modalReappro" tabindex="-1" aria-labelledby="modalReapproLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../models/controleurs/controls_reapro.php" method="POST" id="formReappro">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalReapproLabel">Ajouter un réapprovisionnement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="reapproId">

          <div class="mb-3">
            <label for="quantite_ajoutee" class="form-label">Quantité ajoutée</label>
            <input type="number" step="0.01" class="form-control" id="quantite_ajoutee" name="quantite_ajoutee" required>
          </div>
            <div class="mb-3">
                <label for="date_ex" class="form-label">Date d'exécution</label>
                <input type="date" class="form-control" id="date_ex" name="date_entre" required>
            </div>
        
          <div class="mb-3">
            <label for="date_exp" class="form-label">Date d'expiration</label>
            <input type="date" class="form-control" id="date_exp" name="date_exp">
          </div>

          <div class="mb-3">
            <label for="id_produit" class="form-label">Produit</label>
            <select class="form-select" id="id_produit" name="id_produit" required>
              <option value="">-- Sélectionner un produit --</option>
              <?php foreach ($produits as $prod): ?>
                <option value="<?= $prod['id_produit'] ?>"><?= htmlspecialchars($prod['nom_produit']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="id_fournisseur" class="form-label">Fournisseur</label>
            <select class="form-select" id="id_fournisseur" name="id_fournisseur" required>
              <option value="">-- Sélectionner un fournisseur --</option>
              <?php foreach ($fournisseurs as $four): ?>
                <option value="<?= $four['id_fournisseur'] ?>"><?= htmlspecialchars($four['noms']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="submit" name="ajouter" class="btn btn-primary">Enregistrer</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Remplir le formulaire pour la modification
  document.querySelectorAll('.btnEdit').forEach(btn => {
    btn.addEventListener('click', function() {
      const modal = document.getElementById('modalReappro');
      modal.querySelector('.modal-title').textContent = 'Modifier un réapprovisionnement';
      document.getElementById('reapproId').value = this.dataset.id;
      document.getElementById('quantite_ajoutee').value = this.dataset.quantite;
      document.getElementById('date_ex').value = this.dataset.dateex;
      document.getElementById('date_exp').value = this.dataset.dateexp;
      document.getElementById('id_produit').value = this.dataset.idproduit;
      document.getElementById('id_fournisseur').value = this.dataset.idfournisseur;
    });
  });

  // Réinitialiser le formulaire à l'ouverture du modal d'ajout
  var modalReappro = document.getElementById('modalReappro');
  modalReappro.addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget.classList.contains('btnEdit')) {
      modalReappro.querySelector('.modal-title').textContent = 'Ajouter un réapprovisionnement';
      document.getElementById('formReappro').reset();
      document.getElementById('reapproId').value = '';
    }
  });
</script>

</body>
</html>
