<?php
include 'core/init.php';
include 'includes/check_connect.php';
if (isset($_SESSION['user_id'])) {
	$project_id = mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0); //sets the project ID
}
if ($project_id == 0) {  //redirects if there's no active project for the user (if they've not created one)
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=new_project.php">';
}

if (isset($_REQUEST['restore_character'])) {
	$character_id = (int)$_REQUEST['restore_character'];
	mysql_query("UPDATE `characters` SET `deleted` = 0 WHERE `character_id` = " . $character_id);
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>The Factionizer</title>
		<meta name="keywords" content="factionizer, larp, the factionizer, double exposure, dexcon, dreamation, create larp, convention, gaming">
		<meta name="description" content="A tool for LARP. Create character factions and see their interactions - then print character cards.">
		<meta charset="UTF-8">	
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/primary.css">
		<script src="includes/jquery-1.9.0.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<!--script src="includes/jquery.jeditable.mini.js"></script-->
		<script type="text/javascript" src="/includes/javascript_edit.js"></script>
	</head>
<body>
	<?php
	if (empty($_POST) === false) {
		$required_fields = array('project_name', 'faction_1', 'faction_2');  // can add other fields here later
		foreach($_POST as $key=>$value) { //checks for required fields
			if (empty($value) && in_array($key, $required_fields) === true){
				$errors[] = 'Fields marked with an asterisk are required.';
				break 1;
			}
		}
	}
	?>
	<div id="container">
		<h1>The Factionizer - Project: <?php echo $activeProject['project_name'];?></h1>
		<ul class="menu">
			<li><a href="index.php">Home</a></li>
			<li><a href="new_project.php">New</a></li>
			<li><a href="load.php">Open</a></li>
			<li class="selected"><a href="edit_project.php">Change Numbers</a></li>
			<li><a href="grid.php">Grid</a></li>
			<li><a href="character_card.php">Character Cards</a></li>
		</ul>
		<div id="grid_container">	
			<?php

			if (isset($_GET['delete_faction']) && empty($_GET['delete_faction'])==false && isset($_GET['delete_character'])==false) {
				//$project_name = get_project_data($project_id); 
				//echo $_GET['delete_faction']; exit();
				$faction_num = $_GET['delete_faction'];
				$sql = "SELECT `faction_name` FROM `factions` WHERE `project_id` = '$project_id' AND `faction_num` = '$faction_num'";
				$faction_name = mysql_result(mysql_query($sql),0);
				delete_faction($faction_num, $faction_name);
				echo '<meta HTTP-EQUIV="REFRESH" content="0; url=edit_project.php">';
			} else if (isset($_GET['delete_faction']) && empty($_GET['delete_faction']) ==false && isset($_GET['delete_character']) && empty($_GET['delete_character'])==false){
				delete_character($_GET['delete_faction'],$_GET['delete_character']);
				echo '<meta HTTP-EQUIV="REFRESH" content="0; url=edit_project.php">';
			} else if (isset($_GET['success']) && empty($_GET['success'])) {
				echo "<h3>Your data has been updated!</h3>";
			} else if (isset($_GET['submit']) && empty($_GET['submit'])) {
				$project_name = $_POST['project_name'];				
				$faction_data = get_faction_data($project_id);
				$character_data = get_character_data($project_id);
				$faction_loop = 1;
				$character_loop=1;
				while ($faction_loop <=12) {
					if ($faction_data['faction_num_' . $faction_loop . '_deleted'] == 0) {
						//get faction names
						$faction_names['faction_name_' . $faction_loop] = $_POST['faction_name_' . $faction_loop];
						while ($character_loop <= 12) {
							if ($character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_deleted'] == 0) {
								//get character names
								$character_names['faction_' . $faction_loop . '_character_' . $character_loop . '_name'] = $_POST['faction_' . $faction_loop . '_character_' . $character_loop . '_name'];
								//get player names
								$player_names['faction_' . $faction_loop . '_character_' . $character_loop . '_player'] = $_POST['faction_' . $faction_loop . '_character_' . $character_loop . '_player'];
							}
							$character_loop++;
						}
					}
					$faction_loop++;
					$character_loop = 1;
				}
				update_names($project_id, $_POST['project_name'], $faction_names, $character_names, $player_names);			
				echo '<meta HTTP-EQUIV="REFRESH" content="0; url=edit_project.php?success">';
				exit();
			} else if (isset($_GET['rand_names']) && empty($_GET['rand_names'])) {
				update_rand_names($project_id);
			} else {
				if (empty($errors) === false) {
					echo output_errors($errors);
				}
			}
			if (isset($_GET['new']) && empty($_GET['new'])) {
				$new = true;
				echo '<h3><p>Your new project has been created!</p></h3>';
			} else $new = false;
			echo '<h4><p>On this page, you can edit the names and numbers within your project, including the name of your project, the names and numbers of your factions, and the names and numbers of active characters in each faction.</p></h4>';
			if ($new==true) {
				echo '<h4><p>We\'ve created some generic names for you to use for now.</p></h4>';
			}
			
			$project_data = get_project_data($project_id); //these four lines get all the data
			$project_name = $project_data['project_name_1'];
			$faction_data = get_faction_data($project_id);
			$character_data = get_character_data($project_id);
			//formats are all in the format: array_name['faction_name_number'] or array_name['faction_number_character_number_function'] (such as name or ID)
			//additional information for $faction_data['faction_qty']
			
			echo '<form action="edit_project.php?submit" method="post" role="form">';  //beginning of the form
						
			echo "<h3><p>Project Name: 
			<input type=\"text\" name=\"project_name\" value=\"" . $project_name . "\"></input>";
			echo " &nbsp &nbsp &nbsp Number of Factions: 
			<input type=\"text\" name=\"faction_qty\" value=\"" . $faction_data['faction_qty'] . "\"></input></p></h3>";
			$faction_loop = 1;
			$character_loop=1;
			
			while ($faction_loop <=12) { //loops 12 times for 12 factions
				$deleted_faction_qty=0;
				if ($faction_data['faction_num_' . $faction_loop . '_deleted'] == 0) {
					GLOBAL $the_faction;
					$the_faction = $faction_data['faction_name_' . $faction_loop];
					echo "<img src=\"images\\red_x.gif\" id=\"delete_faction_" . $faction_loop . "\" class=\"delete_faction\" alt=\"Delete\">

					&nbsp <b>Faction:</b>
					<input type=\"text\" name=\"faction_name_" . $faction_loop . "\" class=\"edit\" value=\"" . $faction_data['faction_name_' . $faction_loop] . "\"></input>"; 
					//input field for changing faction name, named faction_name_1
					
					//echo "<img src=\"images\\edit.gif\"  alt=\"Edit\">";
					
					echo " &nbsp &nbsp &nbsp Number of Characters: 
					" . $character_data['faction_' . $faction_loop . '_char_qty'];
					
					echo "<!--img src=\"images\\edit.gif\"  class=\"edit_faction_qty\" class=\"faction " . $faction_loop . "\" alt=\"Edit\"-->
					
					</p></h3>"; //input field for changing number of characters in that faction, named faction_1_char_qty
					
					$deleted_character_qty = 0;
					while ($character_loop <= 12) {  //loops 12 times for 12 characters max per faction
						if ($character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_deleted'] == 0) {  //if not marked as deleted, print the character
							echo "<p> &nbsp &nbsp 
							<img src=\"images\\red_x.gif\"  id=\"delete_faction_" . $faction_loop . "_character_" . $character_loop . "\" class=\"delete_character\" alt=\"Delete\">
							&nbsp &nbsp Character: 
							<input type=\"text\" name=\"faction_" . $faction_loop . "_character_" . $character_loop . "_name\" value=\"" . $character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_name'] . "\"></input>
							&nbsp &nbsp  &nbsp Player: 
							<input type=\"text\" name=\"faction_" . $faction_loop . "_character_" . $character_loop . "_player\" value=\"" . $character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_player'] . "\"></input></p>";
						} else {
							$deleted_character_qty++;
							?>
							<a onclick="return confirm('Are you sure you want to restore deleted character <?php echo $character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_name'];?>?')" href="edit_project.php?restore_character=<?php echo $character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_id'];?>">Restore Deleted Character: <?php echo $character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_name'];?></a>
							<?php
						}
						$character_loop++;
					}
					echo "<br /><br />";
				} else {$deleted_faction_qty=1;}
				if ($deleted_faction_qty==1){
					echo 'Add deleted faction';
				}
				$character_loop=1;
				$faction_loop++;
				
			}
			
			echo '<input type="submit" value="Save Project">';
			echo '</form>';
			
			echo '<a href="edit_project.php?rand_names">Create Random Names for Any Blank Names</a>';
		echo '</div>';
	echo '</div>';
echo '</body>';
echo '</html>';
include 'includes/overall/overall_footer.php';