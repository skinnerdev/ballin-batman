<?php
	include 'core/init.php';
	$page = 'contact';
	include 'includes/overall/overall_header.php';
	$from = "factionizer@factionizer.com";
	if (isset($_GET['success']) && empty($_GET['success'])) {
		if (is_logged_in()) {
			$name = $user_data['first_name'];
			$email = $user_data['email'];
		} else {
			$name = $_POST['name'];
			$email = $_POST['email'];
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false && isset($_GET['success']) === true) {
				$errors[] = 'Please enter a valid email address.';
			}	
		}
		$subject = "Factionizer Message From" . $name;
		$comments = sanitize($_POST['comments']);
	}
?>
<h1>Contact Us</h1>
<br>
<p></p>
<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	$message="$name at $email has written you a message!    $comments";
	mail("asirkin@gmail.com", $subject, $message, "From: ".$from);
	?>
	<h2>Thank you for your message!</h2>
	<p>We always like hearing from our friends.<p>
<?php } else { ?>
	<h2>Got something to say?  Say it here!  We're always happy to hear<br>your suggestions and support!</h2><br>
	<form action="contact.php?success" method="POST">
	<ul>
		<li>Name*:<input type="text" name="name"></li>
		<li>Email*:<input type="text" name="email"></li>
		<li><textarea rows="20" cols="20"
		name="comments" onClick="this.value=''">Comments</textarea></li>
		<li><input type="submit" name="submit" value="Submit"/></li>
	</ul>
	</form>
	<?php
	if ((empty($name) && isset($_GET['success'])) || (empty($comments) && isset($_GET['success']))) {
		if (empty($name) && isset($_GET['success'])) {
			$errors[] = 'Please enter a name.';
		}
		if (empty($comments) && isset($_GET['success'])) {
				$errors[] = 'Please enter a message.';
		}
		if ( ! empty($errors)) {
			echo output_errors($errors);
		} else if ( ! isset($_GET['success'])){
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=contact.php?success">';
		}
	}
}
include 'includes/overall/overall_footer.php'; ?>