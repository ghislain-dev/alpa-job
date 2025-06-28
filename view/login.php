<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../bootstrap-icons/bootstrap-icons.css">

  <style>
    body {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .login-container {
      background: #fff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      margin: auto;
    }

    .form-control:focus {
      box-shadow: none;
      border-color: #2575fc;
    }

    .toggle-password {
      cursor: pointer;
    }

    /* Style de l'alerte */
    .alert {
      display: none; /* Par défaut caché */
      padding: 20px;
      margin: 20px;
      border-radius: 5px;
      color: #fff;
      background-color: #f44336;
      font-size: 16px;
      font-family: Arial, sans-serif;
      max-width: 400px;
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .alert .icon {
      margin-right: 10px;
    }
    .alert .close-btn {
      color: #fff;
      font-size: 20px;
      background: none;
      border: none;
      cursor: pointer;
      position: absolute;
      top: 10px;
      right: 10px;
    }
    .alert .close-btn:hover {
      color: #ddd;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <?php include_once('navbar_client.php'); ?>

  <!-- Alerte d'erreur -->
  <div id="error-alert" class="alert">
    <button class="close-btn" onclick="closeAlert()">×</button>
    <i class="fas fa-exclamation-triangle icon"></i>
    <span id="alert-message"></span>
  </div>

  <!-- FORMULAIRE DE CONNEXION -->
  <div class="login-container mt-4">
    <div class="text-center mb-4">
      <i class="bi bi-person-circle" style="font-size: 4rem; color: #2575fc;"></i>
      <h3 class="mt-2">Se connecter</h3>
    </div>
    
    <form id="loginForm" method="post" action="../models/controleurs/controls_login.php">
      <div class="mb-3">
        <label for="username" class="form-label">Nom d'utilisateur</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
          <input type="text" class="form-control" id="username" name="username" required />
        </div>
      </div>
     
      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
          <input type="password" class="form-control" id="password" name="password" required />
          <span class="input-group-text toggle-password"><i class="bi bi-eye-slash" id="toggleIcon"></i></span>
        </div>
      </div>
      <div class="d-flex justify-content-between mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember" />
          <label class="form-check-label" for="remember">Se souvenir</label>
        </div>
        <a href="#">Mot de passe oublié ?</a>
      </div>

      <button type="submit" name="valider" class="btn btn-primary w-100">Connexion</button>
    </form>

    <div class="mt-3 text-center">
      <p>Pas encore de compte ? <a href="creer_compte.php">S'inscrire</a></p>
    </div>
  </div>

  <!-- JS Bootstrap & Mot de passe toggle -->
  <script>
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', () => {
      const type = passwordInput.type === "password" ? "text" : "password";
      passwordInput.type = type;
      toggleIcon.classList.toggle("bi-eye");
      toggleIcon.classList.toggle("bi-eye-slash");
    });

    // Fonction pour fermer l'alerte
    function closeAlert() {
      var alertBox = document.getElementById('error-alert');
      alertBox.style.display = 'none'; // Cacher l'alerte
    }

    // Affichage dynamique de l'alerte
    window.onload = function() {
      var urlParams = new URLSearchParams(window.location.search);
      var errorMsg = urlParams.get('message'); // Récupère le message d'erreur

      if (errorMsg) {
        // Si un message d'erreur existe, affiche l'alerte
        document.getElementById('alert-message').textContent = errorMsg;
        var alertBox = document.getElementById('error-alert');
        alertBox.style.display = 'block'; // Afficher l'alerte
      }
    };
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
