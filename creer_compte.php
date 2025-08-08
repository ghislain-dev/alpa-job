<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(to right, #00b4db, #0083b0);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .register-container {
      background: #fff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 600px;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #0083b0;
    }
    .toggle-password {
      cursor: pointer;
    }
  </style>
</head>
<body>

<?php
  require_once('nav.php');
?>
  <div class="register-container">
    <div class="text-center mb-4">
      <i class="bi bi-person-plus" style="font-size: 3rem; color: #0083b0;"></i>
      <h3 class="mt-2">Créer un compte</h3>
    </div>
    <form id="registerForm">
      <div class="row g-3">
        <div class="col-md-6">
          <label for="nom" class="form-label">Nom</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control" id="nom" name="nom" required />
          </div>
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" class="form-control" id="email" name="email" required />
          </div>
        </div>
        <div class="col-md-6">
          <label for="tel" class="form-label">Téléphone</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
            <input type="text" class="form-control" id="tel" name="tel" required />
          </div>
        </div>
        <div class="col-md-6">
          <label for="genre" class="form-label">Genre</label>
          <select class="form-select" id="genre" name="genre" required>
            <option value="">-- Choisir --</option>
            <option value="homme">Homme</option>
            <option value="femme">Femme</option>
          </select>
        </div>
        <div class="col-md-12">
          <label for="password" class="form-label">Mot de passe</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="password" name="password" required />
            <span class="input-group-text toggle-password"><i class="bi bi-eye-slash" id="toggleIcon"></i></span>
          </div>
        </div>
        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="terms" required />
            <label class="form-check-label" for="terms">
              J'accepte les <a href="#">termes et conditions</a>
            </label>
          </div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </div>
      </div>
    </form>
    <div class="text-center mt-3">
      <p>Déjà un compte ? <a href="#">Se connecter</a></p>
    </div>
  </div>

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

    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      alert("Formulaire soumis avec succès !");
      // Ici, tu peux envoyer les données avec fetch/ajax ou PHP
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
