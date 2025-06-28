<?php
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload
require_once("../connexion/connexion.php");

session_start();

if (!isset($_SESSION['id'])) {
    die("⚠️ Accès non autorisé. Veuillez vous connecter.");
}

$db = new connexion();
$con = $db->getconnexion();

$id_client = $_SESSION['id'];
$id_commande = $_POST['id_commande'] ?? null;

if (!$id_commande) {
    die("❌ Identifiant de commande manquant.");
}

// 1. Récupérer les infos du client
$stmt = $con->prepare("SELECT nom, email FROM client WHERE id_client = ?");
$stmt->execute([$id_client]);
$client = $stmt->fetch();

if (!$client) {
    die("❌ Client introuvable.");
}

// 2. Récupérer les infos du paiement
$stmt = $con->prepare("SELECT p.montant, p.devise, p.date, c.id_commande 
                       FROM paiement p 
                       JOIN commande c ON p.id_commande = c.id_commande 
                       WHERE p.id_commande = ? AND c.id_client = ?");
$stmt->execute([$id_commande, $id_client]);
$paiement = $stmt->fetch();

if (!$paiement) {
    die("❌ Paiement introuvable.");
}

// 3. Générer le contenu HTML du reçu
$html = "
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    h2 { color: #2E86C1; }
</style>
<h2>🧾 Reçu de Paiement</h2>
<p><strong>Client :</strong> {$client['noms']}</p>
<p><strong>Commande :</strong> #{$paiement['id_commande']}</p>
<p><strong>Montant :</strong> {$paiement['montant']} {$paiement['devise']}</p>
<p><strong>Date :</strong> " . date('d/m/Y', strtotime($paiement['date'])) . "</p>
<hr>
<p>Merci pour votre confiance. À bientôt !</p>
";

// 4. Générer le PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfPath = "../recu/recu_commande_{$id_commande}.pdf";
file_put_contents($pdfPath, $dompdf->output());

// 5. Envoi de l’e-mail avec pièce jointe
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'alphajobbutembo@gmail.com'; // À modifier
    $mail->Password = 'wtvy xeol pddi xtjk'; // À modifier
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('alphajobbutembo@gmail.com', 'Service Paiement');
    $mail->addAddress($client['email'], $client['noms']);
    $mail->isHTML(true);
    $mail->Subject = '🧾 Reçu de paiement - Commande #' . $paiement['id_commande'];
    $mail->Body = "Bonjour <strong>{$client['noms']}</strong>,<br>Veuillez trouver en pièce jointe le reçu de votre paiement.<br><br>Cordialement,<br>L’équipe Alpa Job";

    $mail->addAttachment($pdfPath);

    $mail->send();
    echo "📧 Reçu envoyé avec succès à <strong>{$client['email']}</strong>.";

    // Nettoyage du fichier
    unlink($pdfPath);

} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi : {$mail->ErrorInfo}";
}
?>
