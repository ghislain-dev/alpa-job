<?php
require_once("../connexion/connexion.php");
require_once("../vendor/autoload.php"); // Chemin vers Dompdf (à adapter si besoin)

use Dompdf\Dompdf;





// Vérifier que les paramètres sont passés dans l'URL
$id_paiement = $_GET['id_paiement'] ?? null;

$type = $_GET['type'] ?? null;

if (!$id_paiement || !$type) {
    die("Paramètres manquants.");
}

$db = new connexion();
$con = $db->getconnexion();

// Récupérer les infos du paiement et du client
if ($type === 'commande') {
    $sql = "SELECT p.id_paiement, p.montant, p.devise, p.date, c.id_commande, cl.nom AS nom_client
            FROM paiement p
            LEFT JOIN commande c ON c.id_commande = p.id_commande
            LEFT JOIN client cl ON cl.id_client = c.id_client
            WHERE p.id_paiement = ?";
} else {
    $sql = "SELECT pr.id_paiement, pr.montant, pr.date_paiement, r.id_reservation, cl.nom AS nom_client
            FROM paiement_reservation pr
            LEFT JOIN reservation r ON r.id_reservation = pr.id_reservation
            LEFT JOIN client cl ON cl.id_client = r.id_client
            WHERE pr.id_paiement = ?";
}

$stmt = $con->prepare($sql);
$stmt->execute([$id_paiement]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Aucun paiement trouvé.");
}

// Construire le HTML du reçu
$html = '
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Reçu de paiement</title>
<style>
    body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        color: #333;
        margin: 0;
        padding: 30px;
        background: #f9f9f9;
    }
    .receipt-container {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 25px 40px;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        border-radius: 8px;
    }
    .header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }
    .logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-right: 20px;
    }
    h1 {
        font-weight: 700;
        font-size: 26px;
        color: #222;
        flex-grow: 1;
        text-align: center;
        margin: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 15px;
    }
    th {
        text-align: left;
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        font-weight: 600;
        border-radius: 4px 4px 0 0;
    }
    td {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }
    tr:last-child td {
        border-bottom: none;
    }
    .footer {
        margin-top: 40px;
        text-align: center;
        font-style: italic;
        color: #666;
        font-size: 14px;
    }
</style>
</head>
<body>
<div class="receipt-container">
    <div class="header">
        <img src="../assets/img/logo.png" alt="Logo" class="logo" />
        <h1>Reçu de paiement</h1>
    </div>
    <table>
        <tr><th>ID Paiement</th><td>'.htmlspecialchars($data['id_paiement']).'</td></tr>';

if ($type === 'commande') {
    $html .= '<tr><th>ID Commande</th><td>'.htmlspecialchars($data['id_commande']).'</td></tr>';
} else {
    $html .= '<tr><th>ID Réservation</th><td>'.htmlspecialchars($data['id_reservation']).'</td></tr>';
}

$html .= '
        <tr><th>Client</th><td>'.htmlspecialchars($data['nom_client']).'</td></tr>
        <tr><th>Montant</th><td>'.htmlspecialchars($data['montant']).' '.htmlspecialchars($data['devise'] ?? '').'</td></tr>
        <tr><th>Date de paiement</th><td>'.htmlspecialchars($type === "commande" ? $data["date"] : $data["date_paiement"]).'</td></tr>
    </table>
    <div class="footer">Merci pour votre confiance.</div>
</div>
</body>
</html>';

// Générer le PDF avec Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Télécharger ou afficher
$dompdf->stream("recu_paiement.pdf", ["Attachment" => false]);
exit;
