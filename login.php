<?php
include 'core/init.php';
include 'includes/overall/overall_header.php';

if (empty($_POST) === false) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) === true || empty($password) === true) {
		$errors[] = 'You need to enter a user name and password';
	} else if (user_exists($username) === false) {
		$errors[] = 'That user name does not exist.  Please click Register below.';
	} else if (is_user_active($username) === false) {
		$errors[] = 'Please check your email to activate your account';
	} else {
		if (strlen($_POST['password']) <= 7) {
			$errors[] = 'Your password must be at least 8 characters.';
		} else if (strlen($_POST['password']) >= 25) {
			$errors[] = 'Your password must be less than 25 characters.';
		}
		$login = login($username, $password);
		if ($login === false) {
			$errors[] = 'That username and password combination is incorrect.';
		} else {
			$_SESSION['user_id'] = $login;   //set the user session
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';	//redirect to home
			exit();
		}
	}
	//print_r($errors);
	
} else {
	$errors[] = 'No data recieved';
}


	
if (empty($errors) === false) {
	print "<h2>We tried to log you in, but...</h2>";
	echo output_errors($errors);
} else logged_in_redirect();


//echo output_errors($errors);
include 'includes/overall/overall_footer.php';
?>