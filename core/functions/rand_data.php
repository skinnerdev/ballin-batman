<?php
include 'core/init.php';
include 'includes/overall/overall_header.php';

$csvData = file_get_contents($names.csv); 
$csvNumColumns = 1; 
$csvDelim = ";"; 
$data = array_chunk(str_getcsv($csvData, $csvDelim), $csvNumColumns); 

print_r($data);


include 'includes/overall/overall_footer.php';
?>