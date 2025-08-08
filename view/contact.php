<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contactez-nous - Alpa Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .contact-section {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }
    .contact-header {
      font-weight: 600;
      color: #198754;
    }
    .icon {
      color: #198754;
      margin-right: 8px;
    }
  </style>
</head>
<body>

<?php include('nav_inter.php'); ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10 contact-section">
      <div class="row g-4">
        <!-- Infos de contact -->
        <div class="col-md-6 border-end">
          <h2 class="contact-header mb-4">📞 Contactez-nous</h2>
          <p>Vous avez des questions ? Nous sommes là pour vous aider.</p>
          <ul class="list-unstyled mt-4">
            <li class="mb-3"><i class="bi bi-envelope icon"></i><strong>Email :</strong> alphajobbutembo@gmail.com</li>
            <li class="mb-3"><i class="bi bi-telephone icon"></i><strong>Téléphone :</strong> +243 970 000 000</li>
            <li class="mb-3"><i class="bi bi-geo-alt icon"></i><strong>Adresse :</strong> 123, Avenue de l’Emploi, Butembo</li>
          </ul>
          <h5 class="mt-4">📍 Notre localisation</h5>
          <div class="ratio ratio-16x9 rounded shadow-sm mt-2">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15960.778874728368!2d29.2925019!3d0.0969241!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x17602f035e657acf%3A0xc2d111af2e6a4dbd!2sALPA%20JOB!5e0!3m2!1sfr!2scd!4v1714892567890!5m2!1sfr!2scd"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>

          </div>
        </div>

        <!-- Formulaire -->
        <div class="col-md-6">
          <h2 class="contact-header mb-4">✉️ Envoyez-nous un message</h2>
          <form action="envoi.php" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label">Destinataire</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="sujet" class="form-label">Sujet</label>
              <input type="text" class="form-control" id="sujet" name="sujet" required>
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
              <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS + Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
