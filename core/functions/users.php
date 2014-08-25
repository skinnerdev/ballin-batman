<?php

function is_logged_in() {
	return (isset($_SESSION['user_id'])) ? true : false;
}

function get_user_data($user_id) {
	$data = array();
	$user_id = (int)sanitize($user_id);
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	if ($func_num_args > 1) {
		unset($func_get_args[0]);
		$fields = '`' . implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `user_id` = $user_id"));  
		return $data;		
	}
	$data = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `user_id` = $user_id"));  
	return $data;	
}

function is_user_active($username) {
	$username = sanitize($username);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active` = 1"), 0) == 1) ? true : false;
}

function has_access($user_id, $type = 0) {
	$user_id = (int)$user_id;
	$type = (int)$type;
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = $user_id AND `type` = $type"), 0) == 1) ? true : false;
}

function user_count() {
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1"), 0);
}

function user_has_beta() {
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = '{$_SESSION['user_id']}' AND `beta` = 1"), 0) == 1) ? true : false;
}

function add_user_to_beta($comments) {
	$from		=	"factionizer@factionizer.com";
	$subject	=	"Factionizer Beta for $name";
	$name		= 	$user_data['first_name'];
	$email		=	$user_data['email'];
	$comments	=	sanitize($comments);
	$message = "$name is requesting an invitation to the Factionizer Beta using the email $email and has supplied no additional comments.";
	if (($comments !== "Comments") && ! empty($comments)) {
		$message = "$name is requesting an invitation to the Factionizer Beta using the email $email. Additionally, they write:  $comments";
	}
	mail($email, $subject, $message, "From: ".$from);
	mysql_query("UPDATE `users` SET `beta` = 1 WHERE `user_id` = '$session_user_id'");
}

function get_user_list() {
	$q = "
		SELECT * FROM `users`;
	";
	$results = mysql_query($q);
	$users = array();
	while ($row = mysql_fetch_assoc($results)) {
		$users[] = $row;
	}
	return $users;
}

function change_profile_image($user_id, $file_temp, $file_extn) {
	$file_path = 'images/profile/' . substr(md5(time()), 0 ,10) . '.' . $file_extn;
	move_uploaded_file($file_temp, $file_path);
	mysql_query("UPDATE `users` SET `profile` = '" . $file_path . "' WHERE `user_id` = " . (int)$user_id);
}


function mail_users($subject, $body) {
	$query = mysql_query("SELECT `email`, `first_name` FROM `users` WHERE `allow_email` = 1");
	while (($row = mysql_fetch_assoc($query)) !== false) {
		$message = "Hello " . $row['first_name'] . ",\n\n" . $body . "\n\n    --Factionizer.com";
		email($row['email'], $subject, $message);
	}
}

function recover($mode, $email) {
	$mode = sanitize($mode);
	$email = sanitize($email);
	$user_data = get_user_data(user_id_from_email($email), 'user_id', 'first_name', 'username');
	if($mode == 'username') {
		email($email, 'Your Username Recovery - Factionizer', "Hello " . $user_data['first_name'] . ",\n\nThank you for using the Factionizer.  Your username is:\n" . 	$user_data['username'] . "\n\n   ---Factionizer");
	} else if ($mode == 'password') {
		$generated_password = substr(md5(rand(999,999999)), 0, 8);
		change_password($user_data['user_id'], $generated_password);
		update_user($user_data['user_id'], array('password_recover' => '1'));
		email($email, 'Your Password Recovery - Factionizer', "Hello " . $user_data['first_name'] . ",\n\nThank you for using the Factionizer.  Your password has been reset.  Once you log in with this new password, you will be prompted to change it.\n Your new password is:\n\n" . $generated_password . "\n\n   ---Factionizer");
	} else echo 'Error.';
	
}

function update_user($user_id, $update_data) {
	$user_id = (int)$user_id;
	$update = array();
	array_walk($update_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data) {
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}
	
	return (mysql_query("UPDATE `users` SET " . implode(', ', $update) . " WHERE `user_id` = '$user_id'")) ? true : false;
}


function activate($email, $email_code) {
	$email		= mysql_real_escape_string($email);
	$email_code	= mysql_real_escape_string($email_code);
	
	if (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0"), 0) == 1) {
		mysql_query("UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
		return true;
	} else {
		return false;
	}
}

function change_password($user_id, $password) {
	$user_id = (int)$user_id;
	$password = hash_password($password);
	mysql_query("UPDATE `users` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = $user_id");
}

function register_user($register_data) {
	array_walk($register_data, 'array_sanitize');
	$passwordHash = hash_password($register_data['password']);
	if ($passwordHash == false) {
		echo "There was a problem registering your account. Please try again.";
		return false;
	}
	$register_data['password'] = $passwordHash;		
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
	$data = '\'' . implode('\', \'', $register_data) . '\'';
	
	mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
	//turns this into
	// INSERT INTO `users` (`username`, `password`, `first_name`, `last_name`, `email`) VALUES ('alexander', 'dd22141acb5ea065acd5ed773729c98f', 'alexander', 'alexander', 'alexander@alexander.com')
	
	$emailto = $register_data['email'];
	$subject = 'Activate your account';
	$message = "Hello " . $register_data['first_name'] . ",\n\nThanks for signing up for the Factionizer!\nActivate your account by clicking the link below.\n\n http://factionizer.com/activate.php?email=" . $register_data['email'] . "&email_code=" . $register_data['email_code'] . "\n\n  --Factionizer.com";
	$from = 'factionizer@factionizer.com';
	
	mail($emailto, $subject, $message, "From: ".$from);
}



function user_exists($username) {
	$username = sanitize($username);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'"), 0) == 1) ? true : false;
	//$query = "SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'";
	//if (mysqli_query($connection, $query) == 1) {return true;} else {return false;}
}

function email_exists($email) {
	$email = sanitize($email);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'"), 0) == 1) ? true : false;
	//$query = "SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'";
	//if (mysqli_query($connection, $query) == 1) {return true;} else {return false;}
}

function user_id_from_username($username) {
	$username = sanitize($username);
	return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `username` = '$username'"), 0, 'user_id');
	//$query = "SELECT `user_id` FROM `users` WHERE `username` = '$username'";
	//if (mysqli_query($connection, $query) = 0) {return 0;} else {return 'user_id';}
}

function user_id_from_email($email) {
	$email = sanitize($email);
	return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `email` = '$email'"), 0, 'user_id');
	//$query = "SELECT `user_id` FROM `users` WHERE `email` = '$email'";
	//if (mysqli_query($connection, $query) = 0) {return 0;} else {return 'user_id'}
}

function login($username, $password) {
	$username = sanitize($username);
	$q = "
		SELECT * FROM `users` WHERE `username` = '$username' LIMIT 1;
	";
	$data = mysql_fetch_assoc(mysql_query($q));
	if (password_verify($password, $data['password'])) {
		return $data['user_id'];
	}
	return false;
}

function hash_password($password) {
	$return = password_hash($password, PASSWORD_BCRYPT);
	return $return;
}