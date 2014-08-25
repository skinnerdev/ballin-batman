<div class="widget">  
	<h2>Hello <?php echo $user_data['first_name']; ?>!</h2>
	<div class="inner">
		<div class="profile">		
		<?php
		if (isset($_FILES['profile']) === true) {
			if (empty($_FILES['profile']['name']) === true) {
				echo 'Upload a Profile Picture<br>';
			} else {
				$allowed = array('jpg', 'jpg', 'gif', 'png');
				$file_name = $_FILES['profile']['name'];  //file name
				$file_extn = strtolower(end(explode('.', $file_name)));  //the last word after the last period = the extension
				$file_temp = $_FILES['profile']['tmp_name'];
				//need to add a file size limit
				 if (in_array($file_extn, $allowed) === true) {
					change_profile_image($session_user_id, $file_temp, $file_extn);
					echo '<meta HTTP-EQUIV="REFRESH" content="0; url=' . $current_file . '">';
				 } else {
					echo 'Incorrect file type. Please choose: ';
					echo implode(', ', $allowed);
				 }
			}
		}

		if(empty($user_data['profile']) === false) : ?>
			<img src="<?php echo $user_data['profile'];?>" alt="<?php echo $user_data['first_name'];?>'s Profile Image">
		<?php else: ?>
			<form action="" method="post" enctype="multipart/form-data">
				<input type="file" name="profile"> <input type="submit">
			</form>
			<?php endif; ?>
		</div>
		<ul>
			<?php if ( ! user_has_beta() && SITE_STATUS != RELEASE) : ?>
			<li><a href="beta.php">Sign Up for the Beta!</a></li>
			<?php endif; ?>
			<li><a href="change_password.php">Change Password</a></li>
			<li><a href="<?php echo $user_data['username']; ?>">View Profile</a></li>
			<li><a href="settings.php">Change Settings</a></li>
			<?php if (has_access($user_data['user_id'], USER_TYPE_ADMIN) === true) : ?>
			<li><a href="admin.php">Administration</a></li>
			<?php endif; ?>
			<li><a href="logout.php">Log Out</a></li>
		</ul>
	</div>
</div>