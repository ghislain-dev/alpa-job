<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Chemin vers autoload de Composer

$feedback = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destinataire = $_POST['email'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];

    if (!filter_var($destinataire, FILTER_VALIDATE_EMAIL)) {
        $feedback = "❌ Adresse e-mail invalide.";
    } else {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug  = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'alphajobbutembo@gmail.com'; // À adapter
            $mail->Password   = 'wtvy xeol pddi xtjk'; // Mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('alphajobbutembo@gmail.com', 'Alpa Job Butembo');
            $mail->addAddress($destinataire);

            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body    = nl2br(htmlspecialchars($message));
            $mail->AltBody = $message;

            $mail->send();
            $feedback = "✅ Message envoyé avec succès !";
        } catch (Exception $e) {
            $feedback = "❌ Erreur : {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Envoi de mail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">📨 Envoyer un email avec PHPMailer</h3>

    <?php if (!empty($feedback)): ?>
        <div class="alert alert-info"><?= $feedback ?></div>
    <?php endif; ?>

    <form method="POST" class="card shadow p-4">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email du destinataire</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="ex: client@gmail.com" required>
        </div>
        <div class="mb-3">
            <label for="sujet" class="form-label">Sujet</label>
            <input type="text" class="form-control" name="sujet" id="sujet" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer le message</button>
    </form>
</div>
</body>
</html>
