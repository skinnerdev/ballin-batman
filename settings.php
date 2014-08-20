<?php
include 'core/init.php';
protect_page();
include 'includes/overall/overall_header.php';

if (empty($_POST)===false) {
	$required_fields = array('first_name', 'email');  
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields marked with an asterisk are required.';
			break 1;
		}
	}
	
	if (empty($errors) === true) {
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Please enter a valid email address.';
		} else if (email_exists($_POST['email']) === true && $user_data['email'] !== $_POST['email']) {
			$errors[] = 'That email address is already in use.';
		}
	}
}

?>

<h1>Settings</h1>

<?php
if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
	echo 'Your details have been updated.';
} else {


	if (empty($_POST) === false && empty($errors) === true) {
			$update_data = array(
			'first_name' => $_POST['first_name'], 
			'last_name' => $_POST['last_name'], 
			'email' => $_POST['email'], 
			'allow_email' => ($_POST['allow_email'] == 'on') ? 1: 0
		);
		update_user($session_user_id, $update_data);
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=settings.php?success">';
		echo 'Your details have been updated.';
		exit();
		
	} else if (empty($errors) === false) {
		echo output_errors($errors);
	}

	
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
	}
	?>
	<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="profile"> <input type="submit">
	</form>
	<br>
	<br>
	
	
	<form action="" method="post">
	<ul>
		<li>First Name*:<br>
			<input type="text" name="first_name" value="<?php echo $user_data['first_name']; ?>">
		</li>
		<li>Last Name:<br>
			<input type="text" name="last_name" value="<?php echo $user_data['last_name']; ?>">
		</li>
		<li>Email*:<br>
			<input type="text" name="email" value="<?php echo $user_data['email']; ?>">
		</li>
		<li><input type="checkbox" name="allow_email" <?php if ($user_data['allow_email'] == 1) {echo 'checked="checked"';} ?>>Would you like to recieve email from us?
		</li>
		<li><input type="submit" value="Update"></li>
		
	</ul>



	<p></p>

	<?php
}
include 'includes/overall/overall_footer.php';
?>