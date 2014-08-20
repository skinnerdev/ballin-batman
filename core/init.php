<?php
session_start();
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

require 'database/connect.php';
require 'functions/general.php';
require 'functions/users.php';
require 'functions/project.php';
$current_file = explode('/', $_SERVER['SCRIPT_NAME']);
$current_file = end($current_file);
//echo $current_file;

if (logged_in() === true) {
	$session_user_id = $_SESSION['user_id'];
	$user_data = user_data($session_user_id, 'user_id', 'username', 'password', 'first_name', 'last_name', 'email', 'password_recover', 'type', 'allow_email', 'profile', 'beta', 'active_project');  //lets me use the user data in other functions
	if(user_active($user_data['username']) === false) {   //logs a user out if their account is disabled, even if they are browsing at the time
		session_destroy();  
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';
		exit(); 
	} else if ($current_file !== 'change_password.php' && $current_file !== 'logout.php' && $user_data['password_recover'] == 1) {
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=change_password.php?force">';
	}
}

$errors=array();
?>