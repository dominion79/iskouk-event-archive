<?php


// liest den Inhalt einer Datei in einen String
$filename = "./invoice.html";
$handle = fopen($filename, "r");
$html = fread($handle, filesize($filename));
fclose($handle);

//==============================================================
//==============================================================
//==============================================================

include("../mpdf.php");

$mpdf=new mPDF(); 

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================


?>