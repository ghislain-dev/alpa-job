<?php
session_start();

// Vérifier si le client est connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les infos du client

$id_client = $_SESSION['id'];
$nom = $_SESSION['nom'];
$email = $_SESSION['email'];
$image= $_SESSION['photo'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Client - Alpa Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<header>
    <?php
        include('navbar_clientt.php')
    ?>
</header>
<div class="container mt-5">
    <div class="card shadow p-4">
        <div class="d-flex justify-content-center mb-3">
            <img src="../models/controleurs/avatar/<?= htmlspecialchars($image ?: 'default.png') ?>" 
                alt="Photo de profil" 
                class="rounded-circle" 
                width="200" 
                >
        </div>
        <h3 class="text-center">Bienvenue, <?= htmlspecialchars($nom) ?> 👋</h3>
        <p class="text-center text-muted">Email : <?= htmlspecialchars($email) ?></p>

        <div class="row text-center mt-4">
        
           
        </div>
    </div>
</div>

</body>
</html>
