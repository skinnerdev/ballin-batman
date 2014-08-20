<?php
print "<script type=\"text/javascript\">"; 
print "alert('The email address s already registered')"; 
print "</script>";  
exit();
if (isset($_GET['elements_id'])==false&&isset($_GET['user_edited_content'])==false) {
	//posted to server:  id=elements_id&value=user_edited_content
	$element = $_GET['elements_id'];
	$new_data = $_GET['user_edited_content'];
	

}
?>