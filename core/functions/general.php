<?php
function email($to, $subject, $body) {
	mail($to, $subject, $body, 'From: factionizer@factionizer.com');
}

function protect_page() {
	if (is_logged_in() === false) {
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=protected.php">';
		exit();
	} else {
		$session_user_id = $_SESSION['user_id'];
		$user_data = get_user_data($session_user_id, 'user_id', 'username', 'password', 'first_name', 'last_name', 'email', 'password_recover', 'type', 'allow_email', 'profile', 'beta', 'active_project', 'viewed_tutorial');  //lets me use the user data in other functions
		if ($user_data['type'] == false) {
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=inactive.php">';
			exit();
		}
	}
}

function admin_page() {
	global $user_data;
	if (has_access($user_data['user_id'], USER_TYPE_ADMIN) === false) {
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
		exit();
	}
}

function logged_in_redirect() {
	if (is_logged_in() === true) {
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
	}
}

function array_sanitize($items) {
	foreach ($items as &$item) {
		$item = htmlentities(strip_tags(mysql_real_escape_string($item)));
	}
	return $items;
}

function sanitize($data)  {
	return htmlentities(strip_tags(mysql_real_escape_string($data)));
}

function output_errors($errors) {
	return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
}