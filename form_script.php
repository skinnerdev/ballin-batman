<?PHP
$name=$_POST['name'];
$size=$_POST['size'];
$gender=$_POST['gender'];
$message=$_POST['textarea'];

print "<p>$name $size $gender $message</p>";

$num1=1;
$num2=rand(1,100);
$total=$num1/$num2;
$rounded=round($total,2);

print "$num1 / $num2 = $total";
print "$total is about $rounded";

/*
$file="file.html";
file_put_contents($file, $name . PHP_EOL, FILE_APPEND); //remove append if replacing instead

$file="file.csv";
$data=$name . "," . $size . "," . $gender; //don't need spaces
file_put_contents($file, $data . PHP_EOL, FILE_APPEND);

$from="factionizer@factionizer.com";
$email="asirkin@gmail.com";
$subject="Testing Factionizer";

mail($email, $subject, $message, "From: ".$from);  //from is optional
*/

?>
