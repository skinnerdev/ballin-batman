<?php include 'core/init.php';?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/input.css">
	<script src="includes/jquery-1.9.0.min.js"></script>
	<script type="text/javascript" src="/includes/javascript_grid.js"></script>
	<script type="text/javascript" src="/includes/colorbox.js"></script>
</head>
<body>
<?php

protect_page();


// Determine type, and determine if data is not set
if (isset($_GET['c2c'])) {
	$type == "c2c";
} else if (isset($_GET['c2f'])) {
	$type == "c2f";
} else if (isset($_GET['f2f'])) {
	$type == "f2f";
} else if (isset($_GET['success'])) {
	$type == "success";
} else {
	echo "No type set";
	exit();
}

if ((!isset($_GET['bearer']) || empty($_GET['bearer']) == true || !isset($_GET['receiver']) || empty($_GET['receiver']) == true) && ($type != "success";)) {
	echo "Invalid input.";
	exit();
}

$user_id = $_SESSION['user_id'];
$bearer_id = $_GET['bearer'];
$receiver_id = $_GET['receiver'];
$project_id = mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0);






if ($type == "c2c") {
	$data_from = "c2c";
	$result = mysql_query("SELECT `character_1_id`, `character_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2c` WHERE `project_id` = '$project_id' AND `character_1_id` = '$bearer_id' AND `character_2_id` = '$receiver_id' ORDER BY `character_1_id`") or die(mysql_error);

	if (mysql_num_rows($result)==0) {
		$data_from = "c2f";
		$result = mysql_query("SELECT `character_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2f` WHERE `project_id` = '$project_id' AND `character_1_id` = '$bearer_id' AND `faction_2_id` = '$receiver_id' ORDER BY `character_1_id`") or die(mysql_error);
		if (mysql_num_rows($result)==0) {
			$data_from = "f2f";
			$result = mysql_query("SELECT `faction_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_f2f` WHERE `project_id` = '$project_id' AND `faction_1_id` = '$bearer_id' AND `faction_2_id` = '$receiver_id' ORDER BY `faction_1_id`") or die(mysql_error);
			if (mysql_num_rows($result)==0) {
				$data_from = "none";
				$opinion_word = "Neutral";
				$opinion_text = "Neutral";
			} else {
				$opinion_word = mysql_result(mysql_query("SELECT `opinion_word` FROM `opinions_f2f` WHERE `project_id`='$project_id' AND `faction_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
				$opinion_text = mysql_result(mysql_query("SELECT `opinion_text` FROM `opinions_f2f` WHERE `project_id`='$project_id' AND `faction_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
			}
		} else {
			$opinion_word = mysql_result(mysql_query("SELECT `opinion_word` FROM `opinions_c2f` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
			$opinion_text = mysql_result(mysql_query("SELECT `opinion_text` FROM `opinions_c2f` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
		}
	} else {
		$opinion_word = mysql_result(mysql_query("SELECT `opinion_word` FROM `opinions_c2c` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `character_2_id`='$receiver_id'"), 0) or die(mysql_error);
		$opinion_text = mysql_result(mysql_query("SELECT `opinion_text` FROM `opinions_c2c` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `character_2_id`='$receiver_id'"), 0) or die(mysql_error);
	}
	
} else if ($type == "c2f") {
	$data_from = "c2f";
	$result = mysql_query("SELECT `character_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2f` WHERE `project_id` = '$project_id' AND `character_1_id` = '$bearer_id' AND `faction_2_id` = '$receiver_id' ORDER BY `character_1_id`") or die(mysql_error);
	if (mysql_num_rows($result)==0) {
		$data_from = "f2f";
		$result = mysql_query("SELECT `faction_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_f2f` WHERE `project_id` = '$project_id' AND `faction_1_id` = '$bearer_id' AND `faction_2_id` = '$receiver_id' ORDER BY `faction_1_id`") or die(mysql_error);
		if (mysql_num_rows($result)==0) {
			$data_from = "none";
			$opinion_word = "Neutral";
			$opinion_text = "Neutral";
		} else {
			$opinion_word = mysql_result(mysql_query("SELECT `opinion_word` FROM `opinions_f2f` WHERE `project_id`='$project_id' AND `faction_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
			$opinion_text = mysql_result(mysql_query("SELECT `opinion_text` FROM `opinions_f2f` WHERE `project_id`='$project_id' AND `faction_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
		}
	} else {
		$opinion_word = mysql_result(mysql_query("SELECT `opinion_word` FROM `opinions_c2f` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
		$opinion_text = mysql_result(mysql_query("SELECT `opinion_text` FROM `opinions_c2f` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
	}
} else if ($type == "f2f") {
	$data_from = "f2f";
	$result = mysql_query("SELECT `faction_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_f2f` WHERE `project_id` = '$project_id' AND `faction_1_id` = '$bearer_id' AND `faction_2_id` = '$receiver_id' ORDER BY `faction_1_id`") or die(mysql_error);
	if (mysql_num_rows($result)==0) {
		$data_from = "none";
		$opinion_word = "Neutral";
		$opinion_text = "Neutral";
	} else {
		$opinion_word = mysql_result(mysql_query("SELECT `opinion_word` FROM `opinions_f2f` WHERE `project_id`='$project_id' AND `faction_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
		$opinion_text = mysql_result(mysql_query("SELECT `opinion_text` FROM `opinions_f2f` WHERE `project_id`='$project_id' AND `faction_1_id`='$bearer_id' AND `faction_2_id`='$receiver_id'"), 0) or die(mysql_error);
	}
}






//SUCCESS SECTION

if ($type='success' && isset($_GET['success']) && empty($_GET['success']) == true) {  //update the SQL
	$opinion_word = $_POST['opinion_word'];
	if ($opinion_word == "") {$opinion_word = "x";}
	$opinion_text = $_POST['opinion_text'];
	$opinion_text = mysql_real_escape_string($opinion_text);
	if ($opinion_text == "") {$opinion_text = "x";}
	$result = mysql_query("SELECT `character_1_id`, `character_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2c` WHERE `project_id` = '$project_id' AND `character_1_id` = '$bearer_id' AND `character_2_id` = '$receiver_id' ORDER BY `character_1_id`") or die(mysql_error);
	if (mysql_num_rows($result)==0) {
		//create the opinion
		mysql_query("INSERT INTO `opinions_c2c` (`character_1_id`, `character_2_id`, `opinion_word`, `opinion_text`, `project_id`) VALUES ('$bearer_id', '$receiver_id', '$opinion_word', '$opinion_text', '$project_id')") or die(mysql_error);
		echo "I am creating the opinion		<script> 		parent.jQuery.colorbox.close();	parent.location.reload();	</script>";
		exit();				
	} else {
		//update the opinion
		$opinion_word_database = mysql_result(mysql_query("SELECT `opinion_word` FROM `opinions_c2c` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `character_2_id`='$receiver_id'") or die(mysql_error), 0);
		$opinion_text_database = mysql_result(mysql_query("SELECT `opinion_text` FROM `opinions_c2c` WHERE `project_id`='$project_id' AND `character_1_id`='$bearer_id' AND `character_2_id`='$receiver_id'") or die(mysql_error), 0);
		$opinion_text_database = preg_replace('/[^a-z0-9\s]/i', '', $opinion_text_database);
		if ($opinion_word_database == $opinion_word AND $opinion_text_database == $opinion_text) {
			//no update, data has not changed
			echo "Not updated		<script>		parent.jQuery.colorbox.close();	parent.location.reload();	</script>";
			exit();
		} else {
			mysql_query("UPDATE `opinions_c2c` SET `opinion_word`= '$opinion_word', `opinion_text`= '$opinion_text' WHERE `project_id` = '$project_id' AND `character_1_id` = '$bearer_id' AND `character_2_id` = '$receiver_id'") or die(mysql_error);
			echo "<script>parent.jQuery.colorbox.close();	parent.location.reload();	</script>";
			exit();
		}
	}
	//close the color box and refresh grid.php
}


 // NORMAL INPUT SECTION

if ($opinion_word == "Trust") {$checked1 = "checked";} else {$checked1 = "";}
if ($opinion_word == "Distrust") {$checked2 = "checked";} else {$checked2 = "";}
if ($opinion_word == "Neutral") {$checked3 = "checked";} else {$checked3 = "";}
if ($opinion_word == "Unknown") {$checked4 = "checked";} else {$checked4 = "";}
if ($opinion_word == "Lust") {$checked5 = "checked";} else {$checked5 = "";}
if ($opinion_word == "Love") {$checked6 = "checked";} else {$checked6 = "";}
if ($opinion_word == "Hate") {$checked7 = "checked";} else {$checked7 = "";}
if ($opinion_word == "S/O") {$checked8 = "checked";} else {$checked8 = "";}
if ($opinion_word == "Married") {$checked9 = "checked";} else {$checked9 = "";}
if ($opinion_word == "Related") {$checked10 = "checked";} else {$checked10 = "";}
if ($opinion_word == "Connection") {$checked11 = "checked";} else {$checked11 = "";}
if ($opinion_word == "Special") {$checked12= "checked";} else {$checked12 = "";}

if ($checked1=="" && $checked2=="" && $checked3=="" && $checked4=="" && $checked5=="" && $checked6=="" && $checked7=="" && $checked8=="" && $checked9=="" && $checked10=="" && $checked11=="" && $checked12=="") {echo "Error - Opinion word";}

echo '<div id="inputcontainer">';
echo '<form autocomplete="off" enctype="multipart/form-data" action="input.php?bearer=' . $bearer_id . '&receiver=' . $receiver_id . '&success" novalidate="" name="input" method="POST"><h2>'; 
echo '<input ' . $checked1 . ' type="radio" name="opinion_word" value="Trust"> Trust<br>';
echo '<input ' . $checked2 . ' type="radio" name="opinion_word" value="Distrust"> Distrust<br>';
echo '<input ' . $checked3 . ' type="radio" name="opinion_word" value="Neutral"> Neutral<br>';
echo '<input ' . $checked4 . ' type="radio" name="opinion_word" value="Unknown"> Unknown<br>';
echo '<input ' . $checked5 . ' type="radio" name="opinion_word" value="Lust"> Lust<br>';
echo '<input ' . $checked6 . ' type="radio" name="opinion_word" value="Love"> Love<br>';
echo '<input ' . $checked7 . ' type="radio" name="opinion_word" value="Hate"> Hate<br>';
echo '<input ' . $checked8 . ' type="radio" name="opinion_word" value="S/O"> Significant Other<br>';
echo '<input ' . $checked9 . ' type="radio" name="opinion_word" value="Married"> Married<br>';
echo '<input ' . $checked10 . ' type="radio" name="opinion_word" value="Related"> Related<br>';
echo '<input ' . $checked11 . ' type="radio" name="opinion_word" value="Connection"> Connection<br>';
echo '<input ' . $checked12 . ' type="radio" name="opinion_word" value="Special"> Special<br></h2>';

echo '<br><h2><textarea name="opinion_text" rows="10" cols="50">';
if ($opinion_text == "x") {
	echo "";
} else {
	echo $opinion_text;
}
echo '</textarea></h2><br>';
echo '<input type="submit" value="Save">';
echo '<input type="password" class="hide" name="type" value="' . $type . '">';
echo '<input type="password" class="hide" name="bearer_id" value="' . $bearer_id . '">';
echo '<input type="password" class="hide" name="receiver_id" value="' . $receiver_id . '">';

echo '</form>';
echo '</div>';

?>
</body>
</html>