<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement Mobile Money - Simulation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <div class="card shadow rounded-4">
          <div class="card-header text-center bg-primary text-white rounded-top-4">
            <h4><i class="fas fa-money-bill-wave"></i> Paiement Mobile Money (Simulation)</h4>
          </div>
          <div class="card-body">

            <form action="../models/controleurs/paiement.php" method="POST">
              <div class="mb-3">
                <label class="form-label"><i class="fas fa-user"></i> Nom complet</label>
                <input type="text" name="nom" class="form-control" placeholder="Ex: Jean Kambale" required>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-phone"></i> Téléphone</label>
                <input type="text" name="telephone" class="form-control" placeholder="24397XXXXXXX" required>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-coins"></i> Montant à payer (CDF)</label>
                <input type="number" name="montant" class="form-control" value="1000" required>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-wallet"></i> Mode de paiement</label>
                <select name="mode" class="form-select" required>
                  <option value="Airtel Money">Airtel Money</option>
                  <option value="M-Pesa">M-Pesa</option>
                  <option value="Orange Money">Orange Money</option>
                </select>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-check-circle"></i> Payer maintenant
                </button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Bootstrap JS (facultatif) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
