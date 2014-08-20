<div class="widget">  
	<h2>Hello 
	<?php echo $user_data['first_name']; ?>!</h2>
	<div class="inner">
		<div class="profile">		
			<?php
			
				if (isset() === true
			
				if(empty($user_data[';profile']) === false) {
					echo '<img src="', $user_data['profile'], '" alt="', $user_data['first_name'], '\'s Profile Image">';
				} else {
					echo 'Upload a Profile Picture<br>';
					?>
					<form action="" method="post" enctype="multipart/form-data">
					<input type="file" name="profile"> <input type="submit">
					</form>
					<?php
				}
			?>
		</div>
		<ul>
			<li><a href="beta.php">Sign Up for the Beta!</a> </li> 
			<li><a href="logout.php">Log Out</a> </li>
			<li><a href="change_password.php">Change Password</a></li> <!--Change Password-->
			<li><a href="<?php echo $user_data['username']; ?>">Profile</a></li>
			<li><a href="settings.php">Change Settings</a></li>
			<?php if (has_access($user_data['user_id'], 1) === true) {
				echo '<li><a href="admin.php">Administration</a></li>';
				echo '<li><a href="mail.php">Email Users</a></li>';
				}
			?>
		</ul>
				
	</div>
	
</div>