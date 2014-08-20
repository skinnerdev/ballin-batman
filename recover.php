<?php
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/overall_header.php';

if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
	echo '<h1>Recover</h1>';
	echo '<p>Thanks!  We have emailed you with a recovery link.</p>';

} else {
	$mode_allowed = array('username', 'password');
	if (isset($_GET['mode']) === true && in_array($_GET['mode'], $mode_allowed)===true) {
		if(isset($_POST['email']) === true && empty($_POST['email']) === false) {
			if (email_exists($_POST['email']) === true) {
			recover($_GET['mode'], $_POST['email']);
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=recover.php?success">';
			} else {
				echo '<p>Oops, we couldn\'t find that email address.</p>';
			}
		}

		?>
			<h1>Recover</h1><br>
			<form action="" method="post">
				<ul>
					<li>
						Please enter your email address:<br>
						<input type="text" name="email">			
					</li>
					<li>
						<input type="submit" name="Recover">			
					</li>
				</ul>
			</form>
		<?php
	} else {
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
	echo 'Data change failure';
	exit();
	}
}

include 'includes/overall/overall_footer.php';
?>