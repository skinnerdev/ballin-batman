<?php
function email($to, $subject, $body) {
	mail($to, $subject, $body, 'From: factionizer@factionizer.com');
}


function protect_page() {
	if (logged_in() === false) {
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=protected.php">';
		exit();
	}
}

function admin_page() {
	global $user_data;
	if (has_access($user_data['user_id'], 1) === false) {
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
		exit();
	}
}

function logged_in_redirect() {
	if (logged_in() === true) {
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
	}
}


function array_sanitize($item) {
	$item = htmlentities(strip_tags(mysql_real_escape_string($item)));
}

function sanitize($data)  {
	return htmlentities(strip_tags(mysql_real_escape_string($data)));
}

function output_errors($errors) {
	return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
}

?>