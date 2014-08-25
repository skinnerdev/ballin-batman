<?php
// Easy display for debugging a string or an array
function pr($str) {
	echo "<pre>";
	print_r($str);
	echo "</pre>";
}

function get_site_setting($setting) {
	$q = "
		SELECT value FROM `application_config` WHERE `setting` = '$setting' LIMIT 1;
	";
	$result = mysql_fetch_assoc(mysql_query($q));
	return $result['value'];	
}

function set_site_setting($setting, $value) {
	$value = sanitize($value);
	$q = "
		UPDATE `application_config` SET `value` = '$value' WHERE `setting` = '$setting';
	";
	return (mysql_query($q) == 1) ? true : false;
}

function get_site_status() {
	return get_site_setting('site_status');
}

function get_site_email() {
	return get_site_setting('site_email');	
}