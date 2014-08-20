<?php
include 'core/init.php';
protect_page();
if (empty($_POST) === false) {
	$required_fields = array('current_password', 'password', 'password_again');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields marked with an asterisk are required.';
			break 1;
		}
	}
	if (md5($_POST['current_password']) === $user_data['password']) {
		if (trim($_POST['password']) !== trim($_POST['password_again'])) {
			$errors[] = 'Your new passwords do not match';
		} else if (strlen($_POST['password']) <= 7) {
			$errors[] = 'Your password must be at least 8 characters.';
		} else if (strlen($_POST['password']) >= 25) {
			$errors[] = 'Your password must be less than 25 characters.';
		}
	} else {
		$errors[] = 'Your current password is incorrect';
	}
}
include 'includes/overall/overall_header.php';
	
?>

<h1>Change Password</h1><br>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	echo 'You have successfully changed your password.';
} else {
	if (isset($_GET['force']) === true && empty($_GET['force']) === true) {
		?>
			<p><h2>Your password was reset.  Please change your password.<h2></p>
		<?php
	}
	
	if (empty($_POST) === false && empty($errors) === true) {
		//posted w no errors
		change_password($session_user_id, $_POST['password']);
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=change_password.php?success">';
		exit();
	} else if (empty($errors) === false) {
		//output errors
		echo output_errors($errors);
	}
}
?>

<form action="" method="post">
	<ul>
		<li>
			Current Password*:<br>
			<input type="password" name="current_password">
		</li>
		<li>
		New Password*:<br>
			<input type="password" name="password">
		</li>
		<li>Confirm New Password*:<br>
			<input type="password" name="password_again">
		</li>
		<li>
			<input type="submit" value="Change Password">
		</li>
	</ul>
</form>



<?php include 'includes/overall/overall_footer.php'; ?>