<?php
	include 'core/init.php';
	include 'includes/overall/overall_header.php';
	$from		=	"factionizer@factionizer.com";
	if (isset($_GET['success']) && empty($_GET['success'])) {
	if (logged_in() === true) {
		$name		= 	$user_data['first_name'];
		$email		=	$user_data['email'];
	} else {
		$name		= 	$_POST['name'];
		$email		=	$_POST['email'];
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false && isset($_GET['success']) === true) {
			$errors[] = 'Please enter a valid email address.';
		}	
	}
	$subject	=	"Factionizer Message From" . $name;
	$comments	=	sanitize($_POST['comments']);
	}
?>

<h1>Contact Us</h1><br>
<p></p>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	$message="$name at $email has written you a message!    $comments";
	mail("asirkin@gmail.com", $subject, $message, "From: ".$from);
	?>
	<h2>Thank you for your message!</h2>
	<p>We always like hearing from our friends.<p>
	<?php
} else {
	if (logged_in() === false) {
		?>
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
	} else {
		//if not logged in
		?>
		<p><h2>Got something to say?  Say it here!  We're always happy to hear<br>your suggestions and support!</h2></p><br>
		<form action="contact.php?success" method="POST">
		<ul>
			<li><textarea rows="20" cols="20"
			name="comments" onClick="this.value=''"></textarea></li>
			<li><input type="submit" name="submit" value="Submit"/></li>
		</ul>
		</form>
			
		
		<?php
	}


	if ((empty($name) === true && isset($_GET['success']) === true) || (empty($comments) === true && isset($_GET['success']))) {
		if (empty($name) === true && isset($_GET['success']) === true) {
			$errors[] = 'Please enter a name.';
		}
		if (empty($comments) === true && isset($_GET['success']) === true) {
				$errors[] = 'Please enter a message.';
		}
		if (empty($errors) === false) {
			echo output_errors($errors);
		} else if (isset($_GET['success']) === false){
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=contact.php?success">';
		}
	}
}
include 'includes/overall/overall_footer.php'; ?>