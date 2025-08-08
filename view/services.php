<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nos Services - Alpa Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .service-card {
      border: none;
      border-radius: 12px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .service-icon {
      font-size: 40px;
      color: #0d6efd;
    }
  </style>
</head>
<body>

<!-- Barre de navigation simple -->

<?php require_once('nav_inter.php')?> 

<div class="container my-5">
  <h1 class="text-center mb-4">🌟 Nos Services</h1>
  <p class="text-center mb-5">Découvrez les services proposés par Alpa Job pour répondre à vos besoins.</p>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card service-card h-100 text-center p-4">
        <div class="service-icon mb-3">
          <i class="bi bi-calendar-check"></i>
        </div>
        <h5>Réservation d'espaces</h5>
        <p>Réservez facilement nos espaces pour vos événements, réunions ou formations grâce à notre système en ligne.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card service-card h-100 text-center p-4">
        <div class="service-icon mb-3">
          <i class="bi bi-box-seam"></i>
        </div>
        <h5>Gestion de stock</h5>
        <p>Suivi et gestion en temps réel des produits et matériels pour garantir disponibilité et qualité de service.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card service-card h-100 text-center p-4">
        <div class="service-icon mb-3">
          <i class="bi bi-people"></i>
        </div>
        <h5>Support et accompagnement</h5>
        <p>Une équipe dédiée pour vous accompagner avant, pendant et après vos réservations ou achats.</p>
      </div>
    </div>
  </div>

  <div class="row g-4 mt-4">
    <div class="col-md-4">
      <div class="card service-card h-100 text-center p-4">
        <div class="service-icon mb-3">
          <i class="bi bi-receipt"></i>
        </div>
        <h5>Facturation simplifiée</h5>
        <p>Recevez vos factures et reçus automatiquement après vos paiements, directement par e-mail.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card service-card h-100 text-center p-4">
        <div class="service-icon mb-3">
          <i class="bi bi-pie-chart"></i>
        </div>
        <h5>Tableaux de bord</h5>
        <p>Analysez vos réservations, stocks et commandes grâce à des graphiques et statistiques détaillés.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card service-card h-100 text-center p-4">
        <div class="service-icon mb-3">
          <i class="bi bi-lock"></i>
        </div>
        <h5>Sécurité et confidentialité</h5>
        <p>Vos données sont protégées grâce à des systèmes de sécurité fiables et des sauvegardes régulières.</p>
      </div>
    </div>
  </div>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5">
  &copy; 2025 Alpa Job - Tous droits réservés.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
