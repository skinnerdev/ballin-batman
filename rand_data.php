<?php
include 'core/init.php';
include 'includes/overall/overall_header.php';
$fileName = "names.csv";
$csvData = file_get_contents($fileName); 
$csvNumColumns = 1; 
$csvDelim = ","; 
$data = array_chunk(str_getcsv($csvData, $csvDelim), $csvNumColumns); 
$rand_names = array();
$num_names=1;
while ($num_names<=144):
	$i=rand(0, 3341);
	$rand_name = $data[$i][0];
	$rand_names[$num_names]=$rand_name;
	$num_names++;
endwhile;
print_r($rand_names);

include 'includes/overall/overall_footer.php';
?>