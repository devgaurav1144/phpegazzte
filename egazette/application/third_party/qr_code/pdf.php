<?php

use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

$qrCode = new QrCode('Lorem ipsum sit dolor');

$output = new Output\Png();

// Save black on white PNG image 100px wide to filename.png
$output->output($qrCode, 100, [255, 255, 255], [0, 0, 0], 'filename.png');

?>