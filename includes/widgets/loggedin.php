<div class="widget">  
<h2>Hello 
<?php echo $user_data['first_name']; ?>!</h2>
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

if(empty($user_data['profile']) === false) {
	echo '<img src="', $user_data['profile'], '" alt="', $user_data['first_name'], '\'s Profile Image">';
} else {
	?>
	<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="profile"> <input type="submit">
	</form>
	</div>
<?php } ?>

<ul>
	<li><a href="beta.php">Sign Up for the Beta!</a> </li> 
	<li><a href="logout.php">Log Out</a> </li>
	<li><a href="change_password.php">Change Password</a></li> <!--Change Password-->
	<li><a href="<?php echo $user_data['username']; ?>">Profile</a></li>
	<li><a href="settings.php">Change Settings</a></li>
	<?php 
		if (has_access($user_data['user_id'], 1) === true) {
			echo '<li><a href="admin.php">Administration</a></li>';
			echo '<li><a href="mail.php">Email Users</a></li>';
		}
	?>
</ul>
			
</div>

</div>