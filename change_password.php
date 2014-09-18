<?php
include 'core/init.php';
protect_page();

if ( ! empty($_POST)) {
	$required_fields = array('current_password', 'password', 'password_again');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields marked with an asterisk are required.';
			break;
		}
	}
	if (password_verify(trim($_POST['current_password']), $user_data['password'])) {
		if (trim($_POST['password']) !== trim($_POST['password_again'])) {
			$errors[] = 'Your new passwords do not match';
		} else if (strlen($_POST['password']) <= 7) {
			$errors[] = 'Your password must be at least 8 characters.';
		} else if (strlen($_POST['password']) >= 25) {
			$errors[] = 'Your password must be less than 25 characters.';
		} else {
			change_password($session_user_id, trim($_POST['password']));
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=change_password.php?success">';
			exit();
		}
	} else {
		$errors[] = 'Your current password is incorrect';
	}
}
$message = '';
if (isset($_GET['success'])) {
	$message = '<h2>You have successfully changed your password.<h2>';
} else {
	if (isset($_GET['force']) && empty($_GET['force'])) {
		$message = '<h2>Your password was reset.  Please change your password.<h2>';
	}
}

include 'includes/overall/overall_header.php';
?>

<h1>Change Password</h1><br>
<?php echo ( ! empty($errors)) ? output_errors($errors) : '';?>
<?php echo ( ! empty($message)) ? $message : '';?>
<form action="" method="post">
	<ul>
		<li>
			<label for="current_password">Current Password*:</label><br >
			<input type="password" name="current_password">
		</li>
		<li>
			<label for="password">New Password*:</label><br >
			<input type="password" name="password">
		</li>
		<li>
			<label for="password_again">Confirm New Password*:</label><br >
			<input type="password" name="password_again">
		</li>
		<li>
			<input type="submit" value="Change Password">
		</li>
	</ul>
</form>
<?php include 'includes/overall/overall_footer.php'; ?>