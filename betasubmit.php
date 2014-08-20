<?php
$name=$_POST['name'];
$email=$_POST['email'];
$comments=$_POST['comments'];
$subject=$_POST['Factionizer Beta'];
$emailto="asirkin@gmail.com";
$from="factionizer@factionizer.com";
$subject="Factionizer Beta for $name";



if ($comments != "Comments" && empty($comments) === false) {
	if ($comments != "") 
		$message="$name is requesting an invitation to the Factionizer Beta using the email $email Additionally, they write:<br>$comments";
}
else $message="$name is requesting an invitation to the Factionizer Beta using the email $email and has supplied no additional comments.";

if (isset($name)) {
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		echo "Please enter a valid name and email";
	else {
		mail($emailto, $subject, $message, "From: ".$from);
		echo 'Thank you for submitting your email.';
	}
}


?>