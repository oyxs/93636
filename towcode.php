<?php
include './towcode/phpqrcode.php';
$u=$_GET['u'];
$value="http://www.93636.com/skip.php?u=$u";
$errorCorrectionLevel = "L";
$matrixPointSize = "8";
$margin="1";
QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize,$margin);
exit;
?>