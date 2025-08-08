<?php
// Exemple : vérifier si l'utilisateur est bien comptable
// session_start();
// if ($_SESSION['role'] != 'comptable') { header("Location: login.php"); exit; }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil Comptable</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/nav_admin.css">
</head>
<body class="bg-light">

<div class="container-fluid bg-light ">
    <div class="row align-items-center ">
    
        <?php 
            include_once('nav_comptable.php')
        ?>
        
            <div class="col-md-4 ">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                    <h5 class="card-title">💰 Voir les paiements</h5>
                    <p class="card-text">Consulter et rechercher les paiements des commandes et réservations.</p>
                    <a href="gere_paiement.php" class="btn btn-primary">Accéder</a>
                    </div>
                </div>
            </div>

                <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                    <h5 class="card-title">🧾 Imprimer rapports</h5>
                    <p class="card-text">Générer et imprimer les rapports financiers mensuels ou annuels.</p>
                    <a href="rapport_comptable.php" class="btn btn-success">Imprimer</a>
                    </div>
                </div>
                </div>
       
      
            <div class="col-md-9 justify-content-center mt-5 align-items-center">

                

            </div>
        </div>
  <div class="text-center mt-5">
    <p class="text-muted">© <?php echo date('Y'); ?> Service Comptabilité</p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
