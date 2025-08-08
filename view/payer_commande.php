<?php
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../vendor/autoload.php");
require_once("../connexion/connexion.php");
session_start();

$erreur = "";
$success = "";

if (!isset($_SESSION['id'])) {
    die("⚠️ Accès non autorisé.");
}

$id_client = $_SESSION['id'];
$db = new connexion();
$con = $db->getconnexion();

$id_commande = $_POST['id_commande'] ?? $_GET['id_commande'] ?? null;
if (!$id_commande) {
    $erreur = "❌ Aucune commande sélectionnée.";
} else {
    $stmt = $con->prepare("SELECT montant_total, statut_commande FROM commande WHERE id_commande = ?");
    $stmt->execute([$id_commande]);
    $cmd = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cmd) {
        $erreur = "❌ Commande introuvable.";
    }
}

if (isset($_POST['simuler'])) {
    $operateur = $_POST['operateur'] ?? '';
    $numero = trim($_POST['numero'] ?? '');
    $devise = $_POST['devise'] ?? '';

    if (!preg_match('/^\d{10,12}$/', $numero)) {
        $erreur = "Numéro invalide (10 à 12 chiffres attendus)";
    } elseif (!$operateur || !$devise) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        if (rand(0, 10) > 1) {
            if ($cmd['statut_commande'] !== 'payée') {
                $details = $con->prepare("SELECT id_detail, id_produit, quantite FROM details_commande WHERE id_commande = ?");
                $details->execute([$id_commande]);
                $produits = $details->fetchAll(PDO::FETCH_ASSOC);

                foreach ($produits as $prod) {
                    $id_detail = $prod['id_detail'];
                    $id_produit = $prod['id_produit'];
                    $qte = $prod['quantite'];

                    $reappros = $con->prepare("SELECT id_reapprovisionnement, quantite_reste, date_entre
                        FROM reapprovisionnement 
                        WHERE id_produit = ? AND (statut IS NULL OR statut = 'actif') AND quantite_reste > 0 
                        ORDER BY date_exp ASC");
                    $reappros->execute([$id_produit]);

                    while ($qte > 0 && $r = $reappros->fetch(PDO::FETCH_ASSOC)) {
                        $id_reappro = $r['id_reapprovisionnement'];
                        $reste = $r['quantite_reste'];
                        $date_entre = $r['date_entre'];

                        $a_utiliser = min($qte, $reste);

                        $con->prepare("UPDATE reapprovisionnement SET quantite_reste = quantite_reste - ? WHERE id_reapprovisionnement = ?")
                            ->execute([$a_utiliser, $id_reappro]);

                        $con->prepare("INSERT INTO details_commande_stock (id_detail, id_reapprovisionnement, quantite_utilisee) 
                            VALUES (?, ?, ?)")->execute([$id_detail, $id_reappro, $a_utiliser]);

                        if (($reste - $a_utiliser) <= 0) {
                            $con->prepare("UPDATE reapprovisionnement SET statut = 'épuisé' WHERE id_reapprovisionnement = ?")
                                ->execute([$id_reappro]);
                        }

                        if ($date_entre === null || $date_entre === '0000-00-00') {
                            $con->prepare("UPDATE reapprovisionnement SET date_entre = ? WHERE id_reapprovisionnement = ?")
                                ->execute([date('Y-m-d'), $id_reappro]);
                        }

                        $qte -= $a_utiliser;
                    }

                    if ($qte > 0) {
                        $erreur = "❌ Stock insuffisant pour un ou plusieurs produits.";
                        break;
                    }
                }

                if (empty($erreur)) {
                    $con->prepare("UPDATE commande SET statut_commande = 'payée' WHERE id_commande = ?")
                        ->execute([$id_commande]);

                    $con->prepare("INSERT INTO paiement (montant, devise, date, id_commande) VALUES (?, ?, ?, ?)")
                        ->execute([$cmd['montant_total'], $devise, date('Y-m-d'), $id_commande]);
                }
            }

            if (empty($erreur)) {
                $stmt = $con->prepare("SELECT nom, email FROM client WHERE id_client = ?");
                $stmt->execute([$id_client]);
                $client = $stmt->fetch(PDO::FETCH_ASSOC);

                // 🔄 Récupération des détails de la commande avec nom et prix produit
                $produitsDetails = $con->prepare("SELECT cd.*, p.nom_produit, pr.montant
                    FROM details_commande cd
                    JOIN produit p ON cd.id_produit = p.id_produit
                    JOIN prix pr ON pr.id_prix = p.id_prix
                    WHERE cd.id_commande = ?");
                $produitsDetails->execute([$id_commande]);
                $listeProduits = $produitsDetails->fetchAll(PDO::FETCH_ASSOC);

                // 📄 Construction du reçu HTML
                $html = "
                    <style>
                        body { font-family: DejaVu Sans; }
                        h2 { color:#2E86C1; }
                        table { border-collapse: collapse; width: 100%; margin-top:10px }
                        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 12px; }
                        th { background-color: #f2f2f2; }
                    </style>
                    <h2>🧾 Reçu de Paiement</h2>
                    <p><strong>Client :</strong> " . htmlspecialchars($client['nom']) . "</p>
                    <p><strong>Commande :</strong> #{$id_commande}</p>
                    <p><strong>Opérateur :</strong> " . htmlspecialchars($operateur) . "</p>
                    <p><strong>Numéro :</strong> " . htmlspecialchars($numero) . "</p>
                    <p><strong>Date :</strong> " . date('d/m/Y') . "</p>
                    <hr>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>";
                
                $n = 1;
                $total = 0;
                foreach ($listeProduits as $item) {
                    $sous_total = $item['montant'] * $item['quantite'];
                    $html .= "<tr>
                                <td>{$n}</td>
                                <td>" . htmlspecialchars($item['nom_produit']) . "</td>
                                <td>{$item['quantite']}</td>
                                <td>{$item['montant']} {$devise}</td>
                                <td>{$sous_total} {$devise}</td>
                              </tr>";
                    $n++;
                    $total += $sous_total;
                }

                $html .= "</tbody>
                          <tfoot>
                            <tr>
                              <td colspan='4' style='text-align:right'><strong>Total payé</strong></td>
                              <td><strong>{$total} {$devise}</strong></td>
                            </tr>
                          </tfoot>
                    </table>
                    <hr><p>Merci pour votre confiance.</p>";

                $options = new Options();
                $options->set('isRemoteEnabled', true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                $pdfPath = "../recu/recu_commande_{$id_commande}.pdf";
                file_put_contents($pdfPath, $dompdf->output());

                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'alphajobbutembo@gmail.com';
                    $mail->Password = 'wtvy xeol pddi xtjk';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('alphajobbutembo@gmail.com', 'Service Paiement Alpa Job');
                    $mail->addAddress($client['email'], $client['nom']);
                    $mail->isHTML(true);
                    $mail->Subject = '🧾 Reçu de paiement - Commande #' . $id_commande;
                    $mail->Body = "Bonjour <strong>{$client['nom']}</strong>,<br>
                    Merci pour votre paiement de <strong>{$total} {$devise}</strong>.<br>
                    Votre reçu est en pièce jointe.<br><br>
                    Cordialement,<br><strong>Alpa Job</strong>";
                    $mail->addAttachment($pdfPath);
                    $mail->send();
                    unlink($pdfPath);
                    $success = "✔️ Paiement effectué. Reçu envoyé par e-mail.";
                } catch (Exception $e) {
                    $erreur = "❌ Erreur d'envoi du mail : {$mail->ErrorInfo}";
                }
            }
        } else {
            $erreur = "❌ Transaction refusée par le simulateur.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Simulation Paiement</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <head>
        <?php require_once('navbar_clientt.php')  ?>
    </head>
    
<div class="container mt-5">
    
  <h2>💰 Simulation de Paiement</h2>

  <?php if (!empty($erreur)): ?>
    <div class="alert alert-danger"><?= $erreur ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
    <a href="mes_commandes.php" class="btn btn-primary">Retour à mes commandes</a>
  <?php endif; ?>

  <?php if (empty($success) && empty($erreur)): ?>
    <form method="POST" action="payer_commande.php" >
      <input type="hidden" name="id_commande" value="<?= htmlspecialchars($id_commande) ?>">

      <div class="mb-3">
        <label class="form-label">Opérateur</label>
        <select name="operateur" class="form-select" required>
          <option value="">-- Sélectionnez --</option>
          <option value="Airtel Money">Airtel Money</option>
          <option value="Orange Money">Orange Money</option>
          <option value="M-Pesa">M-Pesa</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Numéro de compte Mobile</label>
        <input type="text" name="numero" class="form-control" placeholder="ex: 243812345678" required>
        <div class="form-text">10 à 12 chiffres attendus</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Devise</label>
        <select name="devise" class="form-select" required>
          <option value="">-- Choisir --</option>
          <option value="USD">USD</option>
          <option value="CDF">CDF</option>
        </select>
      </div>

      <button type="submit"  name="simuler" class="btn btn-success">Simuler le Paiement</button>
      <a href="mes_commandes.php" class="btn btn-secondary ms-2">Annuler</a>
    </form>
  <?php endif; ?>
</div>
</body>
</html>
