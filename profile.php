<?php
include 'core/init.php';
include 'includes/overall/overall_header.php';


if (isset($_GET['username']) === true && empty($_GET['username']) === false) {
	$username = $_GET['username'];
	
	if (user_exists($username) === true) {
		
		$user_id = user_id_from_username($username);
		$profile_data = user_data($user_id, 'first_name', 'last_name', 'email', 'profile');
		//echo '<img src="', $user_data['profile'], '" alt="', $user_data['first_name'], '\'s Profile Image">';
		?>
			<h1><?php echo $profile_data['first_name']; ?>'s Profile</h1><br>
			<p>Howdy!  What would you like to do?</p>

		<?php
	} else {
		echo 'Sorry, that user does not exist.';
	}
} else {
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
	exit();
}
	
?>



<?php include 'includes/overall/overall_footer.php'; ?>