<?php

require __DIR__ . '/vendor/autoload.php';

use Knp\Snappy\Pdf;

$snappy = new Pdf('/wkhtmltox/bin/wkhtmltopdf');
$snappy->generateFromHtml('<h1>Bill</h1><p>You owe me money, dude.</p>', 'demo.pdf');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="file.pdf"');
