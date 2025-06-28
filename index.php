<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alpa Job - Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .hero {
      background: linear-gradient(to right, #005baa, #0082c8);
      color: white;
      padding: 80px 20px;
      text-align: center;
    }
    .icon-service {
      font-size: 3rem;
      color: #0d6efd;
      margin-bottom: 15px;
    }
    footer {
      background-color: #212529;
      color: white;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
  <div class="container">
    <a class="navbar-brand" href="#">Alpa Job</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="view/contact.php">Contact</a></li>
        <li class="nav-item"><a class="btn btn-outline-light ms-2" href="#">Connexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Carousel -->

	<div class="container d-flex justify-content-center my-4">
		<div class="col-md-8">
			<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="max-height: 350px; overflow: hidden; border-radius: 10px;">
			<div class="carousel-indicators">
				<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
				<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
				<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
			</div>
			<div class="carousel-inner">
				<div class="carousel-item active">
				<img src="image_design/index1_carousel.jpg" class="d-block w-100" alt="slide1" style="object-fit: cover; height: 350px;">
				<div class="carousel-caption d-none d-md-block">
					<h5>Gérez vos réservations facilement</h5>
					<p>Une solution simple pour vos espaces et services</p>
				</div>
				</div>
				<div class="carousel-item">
				<img src="image_design/index2_carousel.jpg" class="d-block w-100" alt="slide2" style="object-fit: cover; height: 350px;">
				<div class="carousel-caption d-none d-md-block">
					<h5>Suivi intelligent du stock</h5>
					<p>Visualisez et mettez à jour vos produits en temps réel</p>
				</div>
				</div>
				<div class="carousel-item">
				<img src="image_design/index3_carousel.jpg" class="d-block w-100" alt="slide3" style="object-fit: cover; height: 350px;">
				<div class="carousel-caption d-none d-md-block">
					<h5>Un outil adapté pour toute l’équipe</h5>
					<p>Accessible, sécurisé et pratique</p>
				</div>
				</div>
			</div>
			</div>
		</div>
	</div>

<!-- MODAL : Formulaire de message -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="send_message.php" method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="messageModalLabel"><i class="bi bi-envelope-paper"></i> Écrire un message</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label for="sujet" class="form-label">Sujet</label>
            <input type="text" class="form-control" name="sujet" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" name="message" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Hero -->
<section class="hero">
  <div class="container">
    <h1 class="display-4">Bienvenue chez Alpa Job</h1>
    <p class="lead">Plateforme de gestion moderne pour vos réservations et stocks</p>
    <a href="#services" class="btn btn-light btn-lg mt-3">Découvrir</a>
  </div>
</section>

<!-- Services -->
<section id="services" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5">Nos Services</h2>
    <div class="row text-center">
      <div class="col-md-4">
        <i class="bi bi-calendar-check icon-service"></i>
        <h5>Réservation</h5>
        <p>Réservez vos espaces selon les disponibilités publiées par l’administrateur.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-box-seam icon-service"></i>
        <h5>Stock</h5>
        <p>Suivi en temps réel des produits, alertes automatiques, gestion facile.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-credit-card icon-service"></i>
        <h5>Paiement</h5>
        <p>Interface simple pour le paiement en ligne et la gestion comptable.</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact -->
<section id="contact" class="py-5">
  <div class="container text-center">
    <h2 class="mb-3">Contactez-nous</h2>
    <p>Pour toute question ou besoin d’assistance, notre équipe est à votre écoute.</p>
   	<button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#messageModal">
 		<i class="bi bi-envelope"></i> Écrire un message
	</button>

  </div>
</section>

<!-- Footer -->
<footer class="text-center py-4">
  <p class="mb-0">© 2025 Alpa Job. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
