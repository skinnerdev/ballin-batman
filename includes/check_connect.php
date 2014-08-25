<?php
if( ! isset($_SESSION)) { 
	session_start(); 
}
if (isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
}