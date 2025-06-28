<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Chemin vers l'autoload de Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $destinataire = $_POST['email'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];

    // Vérifier que l'e-mail est valide
    if (!filter_var($destinataire, FILTER_VALIDATE_EMAIL)) {
        die("❌ Adresse e-mail invalide.");
    }

    // Création de l'objet PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Debug SMTP (à 2 ou 3 pour plus de détails si besoin)
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';

        // Configuration du serveur SMTP de Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'alphajobbutembo@gmail.com'; // Ton adresse Gmail
        $mail->Password   = 'wtvy xeol pddi xtjk'; // À remplacer par ton mot de passe d'application Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Informations sur l’expéditeur
        $mail->setFrom('alphajobbutembo@gmail.com', 'Alpa Job Butembo');

        // Destinataire
        $mail->addAddress($destinataire);

        // Contenu du message
        $mail->isHTML(true); // Permet d’envoyer du HTML
        $mail->Subject = $sujet;
        $mail->Body    = nl2br(htmlspecialchars($message)); // Pour éviter le HTML injecté
        $mail->AltBody = $message; // Version texte brut

        // Envoi
        $mail->send();
        echo '✅ Message envoyé avec succès !';
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'envoi : {$mail->ErrorInfo}";
    }
} else {
    echo "Formulaire non soumis.";
}
?>
