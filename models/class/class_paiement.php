<?php
class PaiementModel {
    private $con;
    public function __construct($con) {
        $this->con = $con;
    }

    public function getCommande($id_commande) {
        $stmt = $this->con->prepare("SELECT montant_total, statut_commande FROM commande WHERE id_commande = ?");
        $stmt->execute([$id_commande]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function payerCommande($id_commande, $montant, $devise) {
        $this->con->prepare("UPDATE commande SET statut_commande = 'payée' WHERE id_commande = ?")
                  ->execute([$id_commande]);

        $this->con->prepare("INSERT INTO paiement (montant, devise, date, id_commande) VALUES (?, ?, ?, ?)")
                  ->execute([$montant, $devise, date('Y-m-d'), $id_commande]);
    }

    public function genererRecuPDF($client, $cmd, $id_commande, $operateur, $numero, $devise) {
        $html = "
        <style>body{ font-family: DejaVu Sans; } h2{ color:#2E86C1; }</style>
        <h2>🧾 Reçu de Paiement</h2>
        <p><strong>Client :</strong> " . htmlspecialchars($client['noms']) . "</p>
        <p><strong>Commande :</strong> #{$id_commande}</p>
        <p><strong>Montant :</strong> {$cmd['montant_total']} {$devise}</p>
        <p><strong>Opérateur :</strong> " . htmlspecialchars($operateur) . "</p>
        <p><strong>Numéro :</strong> " . htmlspecialchars($numero) . "</p>
        <p><strong>Date :</strong> " . date('d/m/Y') . "</p>
        <hr><p>Merci pour votre confiance.</p>";

        $options = new Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $path = "../recu/recu_commande_{$id_commande}.pdf";
        file_put_contents($path, $dompdf->output());
        return $path;
    }

    public function envoyerRecuMail($client, $cmd, $id_commande, $devise, $pdfPath) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alphajobbutembo@gmail.com';
        $mail->Password = 'mot_de_passe_app'; // ⚠️ À sécuriser
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('alphajobbutembo@gmail.com', 'Service Paiement Alpa Job');
        $mail->addAddress($client['email'], $client['noms']);
        $mail->isHTML(true);
        $mail->Subject = "🧾 Reçu - Commande #{$id_commande}";
        $mail->Body = "
            Bonjour <strong>{$client['noms']}</strong>,<br>
            Merci pour votre paiement de <strong>{$cmd['montant_total']} {$devise}</strong>.<br>
            Votre reçu est en pièce jointe.<br><br>
            Cordialement,<br><strong>Alpa Job</strong>";
        $mail->addAttachment($pdfPath);
        $mail->send();
    }
}
