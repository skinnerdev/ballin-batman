<ul class="menu">
		<li class="selected"><a href="index.php">Home</a></li>
		<li><a href="beta.php">Beta Signup</a></li>
		<li><a href="contact.php">Contact us</a></li>
		<?php
			include 'includes/check_connect.php';
			if (isset($_SESSION['user_id'])) {
				if (has_access($user_id, 1) === true) {
					echo '<li><a href="edit_project.php">Edit Project</a></li>';
				}
			}
		?>
</ul>