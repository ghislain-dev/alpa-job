<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Envoyer un e-mail</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Formulaire d'envoi d'e-mail</h2>
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
</body>
</html>

