<?php
include 'core/init.php';
include 'includes/overall/overall_header.php';


?>

<h1>Beta Test Signup</h1>


<?php
if (logged_in() === false) {
	echo 'Please register or log in to sign up for the beta!';
} else if ((mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = '$session_user_id' AND `beta` = 0"), 0) == 1) == 0) {
	echo 'You\'ve already signed up for the beta!';
	echo '<p>In the meantime, here\'s a screenshot to keep you interested!</p>
		<img src="images/screenshot.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;"><br>
		<img src="images/screenshot2.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;">';
} else {
	if (isset($_GET['success']) === true && empty($_GET['success']) === true && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != false && empty($name) === false) {
		$from		=	"factionizer@factionizer.com";
		$subject	=	"Factionizer Beta for $name";
		$name		= 	$user_data['first_name'];
		$email		=	$user_data['email'];
		$comments	=	sanitize($_POST['comments']);
		if (($comments !== "Comments") && empty($comments) === false) {
			$message="$name is requesting an invitation to the Factionizer Beta using the email $email Additionally, they write:  $comments";
		} else {
			$message="$name is requesting an invitation to the Factionizer Beta using the email $email and has supplied no additional comments.";
		}
		mail($email, $subject, $message, "From: ".$from);
		mysql_query("UPDATE `users` SET `beta` = 1 WHERE `user_id` = '$session_user_id'");
		?>
		<p><h2>Your application was submitted!</h2></p>
		<p>We will contact you when the Beta is live.<p>
		<p>In the meantime, here's a screenshot to keep you interested!</p>
		<img src="images/screenshot.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;">
		<img src="images/screenshot2.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;">
		<?php
		exit();
	} else {
		if (empty($errors) === false) {
			echo output_errors($errors);
		}
		?>
		<p><h2>The Factionizer is currently in Alpha testing.  If you would like to be a beta tester when this is complete, please fill out the form below:</p></h2>

		<form action="beta.php?success" method="POST">
		<ul>
			<li><textarea rows="20" cols="20"
			name="comments" onClick="this.value=''">Comments</textarea></li>
			<li><input type="submit" name="submit" value="Submit"/></li>
		</ul>

		<?php
	}
}
include 'includes/overall/overall_footer.php'; ?>