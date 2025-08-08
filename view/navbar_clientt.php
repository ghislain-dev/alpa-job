<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  // Détecter la page actuelle
  $current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Alpa Job</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarClient" aria-controls="navbarClient" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarClient">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'affiche_produit.php') ? 'active fw-bold text-warning' : ''; ?>" href="affiche_produit.php">🏠 Accueil</a>
        </li>
       
          <li class="nav-item">

          <a class="nav-link <?php echo ($current_page == 'reservations.php') ? 'active fw-bold text-warning' : ''; ?>" href="reservations.php">📅 listes de réservation</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'mes_commandes.php') ? 'active fw-bold text-warning' : ''; ?>" href="mes_commandes.php">📦listes Commandes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index_client.php') ? 'active fw-bold text-warning' : ''; ?>" href="index_client.php">👤 Mon Profil</a>
        </li>
      </ul>
      <span class="navbar-text me-3 text-light">
        <?= isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : 'Client' ?>
      </span>
      <a href="deconnexion.php" class="btn btn-outline-light">🔒 Déconnexion</a>
    </div>
  </div>
</nav>
