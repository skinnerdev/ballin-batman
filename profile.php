<?php
include 'core/init.php';
include 'includes/overall/overall_header.php';
protect_page();

if ( ! empty($_GET['username'])) {
	$username = $_GET['username'];
	if (user_exists($username)) :
		$user_id = user_id_from_username($username);
		$profile_data = get_user_data($user_id, 'first_name', 'last_name', 'email', 'profile');
		//echo '<img src="', $user_data['profile'], '" alt="', $user_data['first_name'], '\'s Profile Image">';
		?>
		<h1><?php echo $profile_data['first_name']; ?>'s Profile</h1>
		<br>
		<p>Howdy!  What would you like to do?</p>
	<?php else : ?>
		<p>Sorry, that user does not exist.</p>
	<?php endif;
} else {
	header('Location: index.php');
	exit;
}
include 'includes/overall/overall_footer.php'; ?>