<?php
require_once __DIR__ . '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Courier');

$dompdf = new Dompdf($options);
$dompdf->loadHtml('<h1>Hello World</h1>');
$dompdf->render();
$dompdf->stream('document.pdf');
//             $success = "✅ Paiement simulé avec succès !";