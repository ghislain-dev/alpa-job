<?php
  // Détecter la page actuelle
  $current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
  <div class="container">
    <a class="navbar-brand" href="#">Alpa Job</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active fw-bold text-warning' : ''; ?>" href="../index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'services.php') ? 'active fw-bold text-warning' : ''; ?>" href="services.php">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active fw-bold text-warning' : ''; ?>" href="contact.php">Contact</a>
        </li>
         <li class="nav-item">
             <a class="nav-link btn btn-outline-light ms-2 <?php echo ($current_page == 'creer_compte.php') ? 'active fw-bold text-warning' : ''; ?>" href="creer_compte.php">Créer compte</a>
        </li>
        <li class="nav-item">
             <a class="nav-link btn btn-outline-light ms-2 <?php echo ($current_page == 'login.php') ? 'active fw-bold text-warning' : ''; ?>" href="login.php">Connexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
