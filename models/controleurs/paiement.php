<?php
// Simuler la réponse du paiement (aléatoire)
$statut = rand(0, 1) ? "ACCEPTED" : "FAILED";

// Récupérer les données du formulaire
$nom = $_POST['nom'];
$telephone = $_POST['telephone'];
$montant = $_POST['montant'];
$mode = $_POST['mode'];
$date = date("Y-m-d H:i:s");

// Enregistrer dans un fichier (ou base plus tard)
$ligne = "$date | $nom | $telephone | $montant CDF | $mode | $statut\n";
file_put_contents("paiements.txt", $ligne, FILE_APPEND);

// Affichage du résultat
if ($statut == "ACCEPTED") {
  echo "<h2>Paiement réussi ✅</h2>";
  echo "<p>Merci, $nom. Votre paiement de $montant CDF via $mode a été accepté.</p>";
} else {
  echo "<h2>Paiement échoué ❌</h2>";
  echo "<p>Désolé, $nom. Le paiement a échoué. Veuillez réessayer.</p>";
}

echo '<p><a href="index.html">Retour</a></p>';
?>
