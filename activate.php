<?php
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/overall_header.php';
if (isset($_GET['success']) === true && empty($_GET['success']) === true) {

?>
	<h2>We have activated your account!</h2>
	<p>Please log in on the right to check out all the cool stuff.</p>
<?php

} else if (isset($_GET['email'], $_GET['email_code']) === true) {
		$email = trim($_GET['email']);
		$email_code = trim($_GET['email_code']);
		
		if (email_exists($email) === false) {
			$errors[] = 'We could not find that email address';
		} else if (activate($email, $email_code) === false) {
			$errors[] = 'We had a problem activating your account.';
		}
		
		if (empty($errors) === false) {
		?>
			<H2>Oops...</h2>
		<?php
			echo output_errors($errors);
		} else {
			?>
				<h2>We have activated your account!</h2>
				<p>Please log in on the right to check out all the cool stuff.</p>
			<?php
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=activate.php?success">';
			exit();
		}
		
} else {
	//dispay error and redirect
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
	exit();
}

include 'includes/overall/overall_footer.php';
?>