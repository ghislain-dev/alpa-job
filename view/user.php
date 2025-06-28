<?php
require_once('../connexion/connexion.php');
require_once('../models/class/class_user.php');

$db = new connexion();
$con = $db->getconnexion();
$user = new user($con);
$utilisateurs = $user->get_user();
$fonctions = $user->get_fonction();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Utilisateurs - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/nav_admin.css">
</head>
<body>


<div class="container-fluid bg-light ">
<div class="row align-items-center">
    <?php require_once('nav_admin.php') ?>
    <div class="">

    <!-- Messages de succès -->
    <div class="message">
        <?php
        if (isset($_GET['success'])) {
            $success = $_GET['success'];
            $message = '';
            $alertType = 'success';

            if ($success === 'add') {
                $message = '✅ Utilisateur ajouté avec succès.';
            } elseif ($success === 'update') {
                $message = '✏️ Utilisateur modifié avec succès.';
            } elseif ($success === 'sup') {
                $message = '🗑 Utilisateur supprimé avec succès.';
            }

            if (!empty($message)) {
                echo "<div class='alert alert-{$alertType} alert-dismissible fade show mt-3' role='alert'>
                        $message
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fermer'></button>
                    </div>";
            }
        }

        // Message d'erreur optionnel
        if (isset($_GET['error'])) {
            echo "<div class='alert alert-danger mt-3'>❌ Une erreur est survenue. Veuillez réessayer.</div>";
        }
        ?>
    </div>

    <h2 class="mb-4">Liste des utilisateurs</h2>

    <!-- Bouton pour ouvrir la modale d'ajout -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAdd">➕ Ajouter un utilisateur</button>

    <!-- Tableau -->
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Postnom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Numéro</th>
            <th>Fonction</th>
            <th>Image</th>
            <th>Modifier</th>
            <th>Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; foreach ($utilisateurs as $u): ?>
            <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['postnom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['numero']) ?></td>
            <td><?= htmlspecialchars($u['nom_fonction']) ?></td>
            <td>
                <img src="../models/controleurs/avatar/<?= !empty($u['image']) ? htmlspecialchars($u['image']) : 'default.png' ?>" width="60" alt="Image utilisateur">
            </td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $u['id_utilisateur'] ?>">✏️</button>
            </td>
            <td>
                <a href="../models/controleurs/controls_user.php?sup=<?= $u['id_utilisateur'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">🗑</a>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<!-- ✅ Modale AJOUT utilisateur -->
<div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formAdd" method="post" action="../models/controleurs/controls_user.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Ajouter Utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="nom" class="form-control mb-2" placeholder="Nom" required>
        <input type="text" name="postnom" class="form-control mb-2" placeholder="Postnom" required>
        <input type="text" name="prenom" class="form-control mb-2" placeholder="Prénom" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="tel" name="numero" class="form-control mb-2" placeholder="Numéro" required>
        <select name="idfonction" class="form-select mb-2" required>
          <option value="">--Fonction--</option>
          <?php foreach($fonctions as $f): ?>
            <option value="<?= $f['id_fonction'] ?>"><?= htmlspecialchars($f['nom_fonction']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="file" name="image" class="form-control mb-2">

        <div class="mb-3 position-relative">
          <label for="pwd" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" name="pwd" id="pwd" required>
          <i class="bi bi-eye-slash toggle-password" toggle="#pwd" style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></i>
        </div>

        <div class="mb-3 position-relative">
          <label for="confirmer" class="form-label">Confirmer</label>
          <input type="password" class="form-control" name="confirmer" id="confirmer" required>
          <i class="bi bi-eye-slash toggle-password" toggle="#confirmer" style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></i>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="ajouter" class="btn btn-primary">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Toutes les modales de MODIFICATION générées hors du tableau -->
<?php foreach ($utilisateurs as $u): ?>
<div class="modal fade" id="modalEdit<?= $u['id_utilisateur'] ?>" tabindex="-1" aria-labelledby="modalEditLabel<?= $u['id_utilisateur'] ?>" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEdit<?= $u['id_utilisateur'] ?>" method="post" action="../models/controleurs/controls_user.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="modalEditLabel<?= $u['id_utilisateur'] ?>">Modifier Utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $u['id_utilisateur'] ?>">
        <input type="text" name="nom" class="form-control mb-2" value="<?= htmlspecialchars($u['nom']) ?>" required>
        <input type="text" name="postnom" class="form-control mb-2" value="<?= htmlspecialchars($u['postnom']) ?>" required>
        <input type="text" name="prenom" class="form-control mb-2" value="<?= htmlspecialchars($u['prenom']) ?>" required>
        <input type="email" name="email" class="form-control mb-2" value="<?= htmlspecialchars($u['email']) ?>" required>
        <input type="tel" name="numero" class="form-control mb-2" value="<?= htmlspecialchars($u['numero']) ?>" required>
        <select name="idfonction" class="form-select mb-2" required>
          <option value="">Fonction</option>
          <?php foreach($fonctions as $f): ?>
            <option value="<?= $f['id_fonction'] ?>" <?= $f['id_fonction'] == $u['id_fonction'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($f['nom_fonction']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="file" name="image" class="form-control mb-3">

        <div class="mb-3 position-relative">
          <label for="pwd<?= $u['id_utilisateur'] ?>" class="form-label">Nouveau mot de passe</label>
          <input type="password" class="form-control" name="pwd" id="pwd<?= $u['id_utilisateur'] ?>">
          <i class="bi bi-eye-slash toggle-password" toggle="#pwd<?= $u['id_utilisateur'] ?>" style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></i>
        </div>

        <div class="mb-3 position-relative">
          <label for="conf<?= $u['id_utilisateur'] ?>" class="form-label">Confirmer</label>
          <input type="password" class="form-control" name="confirmer" id="conf<?= $u['id_utilisateur'] ?>">
          <i class="bi bi-eye-slash toggle-password" toggle="#conf<?= $u['id_utilisateur'] ?>" style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></i>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="modifier" class="btn btn-warning">Modifier</button>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>

<!-- ✅ Scripts Bootstrap et JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Validation mot de passe pour formulaire d'ajout
document.getElementById("formAdd").addEventListener("submit", function (e) {
  const pwd = this.querySelector('input[name="pwd"]').value;
  const conf = this.querySelector('input[name="confirmer"]').value;
  if (pwd !== conf) {
    e.preventDefault();
    alert("⚠️ Les mots de passe ne correspondent pas !");
  }
});

// Validation pour les formulaires de modification
document.querySelectorAll('form[id^="formEdit"]').forEach(form => {
  form.addEventListener("submit", function (e) {
    const pwd = this.querySelector('input[name="pwd"]').value;
    const conf = this.querySelector('input[name="confirmer"]').value;
    if ((pwd || conf) && pwd !== conf) {
      e.preventDefault();
      alert("⚠️ Les mots de passe ne correspondent pas !");
    }
  });
});

// Afficher / masquer mot de passe
document.querySelectorAll(".toggle-password").forEach(icon => {
  icon.addEventListener("click", function () {
    const input = document.querySelector(this.getAttribute("toggle"));
    if (!input) return;
    input.type = input.type === "password" ? "text" : "password";
    this.classList.toggle("bi-eye-slash");
    this.classList.toggle("bi-eye");
  });
});
</script>

</body>
</html>
