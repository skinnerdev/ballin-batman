<?php
$home = 'class="selected"';
$beta = $contact = '';
if (isset($page)) {
	$home = '';
	${$page} = 'class="selected"';
}
?>
<ul class="menu">
		<li <?php echo $home;?>><a href="index.php">Home</a></li>
		<li <?php echo $beta;?>><a href="beta.php">Beta Signup</a></li>
		<li <?php echo $contact;?>><a href="contact.php">Contact us</a></li>
		<?php
			include 'includes/check_connect.php';
			if (isset($_SESSION['user_id'])) {
				if (has_access($user_id, 1) === true) {
					echo '<li><a href="edit_project.php">Edit Project</a></li>';
				}
			}
		?>
</ul>