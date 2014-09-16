<?php
include 'core/init.php';
include 'includes/check_connect.php';
protect_page();
$project_id = $activeProject['project_id'];
if ($project_id == 0) {  //redirects if there's no active project for the user (if they've not created one)
	header("Location: new_project.php");
	exit;
}

if (isset($_POST['restore_character'])) {
	$character_id = (int)$_POST['restore_character'];
	mysql_query("UPDATE `characters` SET `deleted` = 0 WHERE `character_id` = " . $character_id);
}
$factions = get_project_factions($project_id);
$character_data = get_project_characters($project_id);
$deleted_characters = array();
$deleted_factions = array();
foreach ($character_data as $faction_num => $faction) {
	if (isset($factions[$faction_num]) && $factions[$faction_num]['deleted']) {
		$deleted_factions[$faction_num] = $faction;
		unset($character_data[$faction_num]);
		continue;
	}
	foreach ($faction as $key => $character) {
		if ($character['deleted']) {
			$deleted_characters[$faction_num][$key] = $character;
			unset($character_data[$faction_num][$key]);
		}
	}
}

if ( ! empty($_POST)) {
	$required_fields = array('project_name', 'faction_1', 'faction_2');  // can add other fields here later
	foreach($_POST as $key => $value) { //checks for required fields
		if (empty($value) && in_array($key, $required_fields)){
			$errors[] = 'Fields marked with an asterisk are required.';
			break;
		}
	}
}

$new_project = false;
if (isset($_SESSION['new-project'])) {
	$new_project = true;
	unset($_SESSION['new-project']);
}

if (isset($_GET['action'])) {
	$type = '';
	$id = null;
	$action = $_GET['action'];
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	}
	if (isset($_GET['id'])) {
		$id = (int)$_GET['id'];
	}
	$result = null;
	switch ($action) {
		case 'restore':
		case 'delete':
			if ($type == 'character') {
				$result = call_user_func($action . "_" . $type . "_by_id", $id);
				if ($result) {
					echo json_encode("success");
				} else {
					echo json_encode("error");
				}
				exit;
			} else {
				$result = call_user_func($action . "_" . $type . "_by_id", $id);
				if ($result) {
					$_SESSION['edit-project-save-message'] = "Faction " . $action ."d sucessfully";
				} else {
					$result = true;
					$_SESSION['edit-project-save-message'] = "There was a problem with that action.";
				}
			}
			break;
		case 'get-names':
			if (isset($_GET['name-count'])) {
				$name_count = $_GET['name-count'];
				$names = get_random_names($name_count);
				echo json_encode($names);
				exit;
			}
			break;
		case 'add-faction':
			$result = project_add_faction($project_id);
			if ($result) {
				$_SESSION['edit-project-save-message'] = "New faction added. Character names auto-filled.";
			} else {
				$result = true;
				$_SESSION['edit-project-save-message'] = "There was a problem adding a new faction.";
			}
			break;
		case 'save-form':
			if ( ! empty($errors)) {
				break;
			}
			$result = true;
			$saveData = array();
			if (trim($_POST['project_name']) != $activeProject['project_name']) {
				$saveData['project_name'][$activeProject['project_id']] = trim($_POST['project_name']);
			}
			foreach ($_POST['names'] as $faction_num => $names) {
				if ($factions[$faction_num]['faction_name'] != trim($names['faction_name'])) {
					$saveData['factions'][$factions[$faction_num]['faction_id']] = trim($names['faction_name']);
				}
				foreach ($names as $character_number => $character) {
					if ($character_number == 'faction_name') {
						continue;
					}
					$charName = trim($character['character_name']);
					if ( ! empty($charName) && $charName != $character_data[$faction_num][$character_number]['character_name']) {
						$saveData['characters'][$character_data[$faction_num][$character_number]['character_id']]['character_name'] = $charName;
					}
					$characterPriority = trim($character['priority']);
					if ($characterPriority != $character_data[$faction_num][$character_number]['priority']) {
						$saveData['characters'][$character_data[$faction_num][$character_number]['character_id']]['priority'] = $characterPriority;
					}
					$playerName = trim($character['player_name']);
					if ($playerName != $character_data[$faction_num][$character_number]['player_name']) {
						$saveData['characters'][$character_data[$faction_num][$character_number]['character_id']]['player_name'] = $playerName;
					}
					$characterBio = trim($character['character_bio']);
					if ($characterBio != $character_data[$faction_num][$character_number]['character_bio']) {
						$saveData['characters'][$character_data[$faction_num][$character_number]['character_id']]['character_bio'] = $characterBio;
					}
				}
			}
			if ( ! empty($saveData)) {
				if (save_project_data($project_id, $saveData)) {
					if (isset($_GET['result']) && $_GET['result'] == 'json') {
						echo json_encode("success");
						exit;
					}
					$_SESSION['edit-project-save-message'] = "Project and names saved!";
				} else {
					if (isset($_GET['result']) && $_GET['result'] == 'json') {
						echo json_encode("error");
						exit;
					}
				}
			} else {
					if (isset($_GET['result']) && $_GET['result'] == 'json') {
						echo json_encode("success");
						exit;
					}
			}
			break;
	}
	if ($result) {
		header("Location: edit_project.php");
		exit;
	}
}

$saveMessage = null;
if (isset($_SESSION['edit-project-save-message'])) {
	$saveMessage = $_SESSION['edit-project-save-message'];
	unset($_SESSION['edit-project-save-message']);
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
		<link rel="stylesheet"  href="css/bootstrap-tour.min.css">
		<script src="includes/jquery-1.9.0.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/includes/javascript_edit.js"></script>
		<script src="javascript/bootstrap-tour.min.js"></script>
		<script type="text/javascript">
		var user_viewed_tutorial = <?php echo $user_data['viewed_tutorial'];?>;
		var user_id = <?php echo $_SESSION['user_id'];?>;
		</script>
		<script src="javascript/tour.js"></script>
		<style>
		i.fa {
			color: #548B54;
		}
		a.delete_faction i, i.delete-character {
			color: #ff0000;
		}
		a.toggle_character, #random-names, #add-faction, #clear-character-names, #clear-player-names, #clear-character-bio {
			float: left;
			margin-right: 10px;
		}
		.edit-project>ul>li {
			padding-left: 25px;
		}
		</style>
	</head>
	<body>
	<div id="container">
		<div class="logo">
			<img src="/images/Factionizerlogo.png">
		</div>
		<!-- <h1>The Factionizer - Project: <?php echo $activeProject['project_name'];?></h1> -->
		<ul class="menu">
			<li><a href="index.php">Home</a></li>
			<li><a href="new_project.php">New</a></li>
			<li><a href="load.php">Open</a></li>
			<li class="selected"><a href="edit_project.php">Edit Project</a></li>
			<li id="nav-grid"><a href="grid.php">Grid</a></li>
			<li><a href="character_card.php">Character Cards</a></li>
			<li><a href="print.php" target="_blank">Print CC's</a></li>
		</ul>
		<div id="grid_container">	
			<button id="tour-start-edit" class="btn btn-primary" style="float:right;"><i class="fa fa-question-circle" style="color: #ffffff;"></i>&nbsp;Show the Tour</button>
			<form action="edit_project.php?action=save-form" method="post" role="form" id="project-form">
				<div class="edit-project">
				<?php if ( ! empty($errors)) {
					echo output_errors($errors);
				}?>
				<?php if ( ! empty($saveMessage)) {
					echo '<h3 style="color: #548B54">' . $saveMessage . '</h3>';
				}?>
				<?php if ($new_project) : ?>
				<h3><p>Your new project has been created!</p></h3>
				<?php endif; ?>
				<h4><p>On this page, you can edit the names and numbers within your project, including the name of your project, the names and numbers of your factions, and the names and numbers of active characters in each faction.</p></h4>
				<?php if ($new_project) : ?>
				<h4><p>We've created some generic names for you to use for now.</p></h4>
				<?php endif; ?>
				<h3>
					<label for="project_name">Project Name:</label>
					<input type="text" name="project_name" value="<?php echo $activeProject['project_name'];?>" id="project-name"></input>
					&nbsp;&nbsp;&nbsp;Active factions: <span id="faction-count"><?php echo ($activeProject['faction_qty'] - count($deleted_factions));?></span> / <span id="faction-limit"><?php echo FACTION_LIMIT; ?></span>
				</h3>
				<?php if ($activeProject['faction_qty'] < FACTION_LIMIT) : ?>
				<p id="add-faction-container">
					<a id="add-faction"><i class="fa fa-plus fa-2x"></i></a>Add another faction
				</p>
				<?php endif; ?>
				<p>
					<a id="random-names"><i class="fa fa-random fa-2x"></i></a>Randomize Names
				</p>
				<p>
					<a id="clear-character-names"><i class="fa fa-times fa-2x"></i></a>Clear all character names
				</p>
				<p>
					<a id="clear-player-names"><i class="fa fa-times fa-2x"></i></a>Clear all player names
				</p>
				<p>
					<a id="clear-character-bio"><i class="fa fa-times fa-2x"></i></a>Clear all characters Bio
				</p>
				<br >
				<?php foreach ($character_data as $faction_num => $faction_characters) : ?>
				<div id="faction_<?php echo $faction_num;?>">
				<a title="Delete Faction" class="delete_faction" id="delete_faction_<?php echo $faction_num; ?>" data-faction-id="<?php echo $factions[$faction_num]['faction_id'];?>" data-faction-name="<?php echo $factions[$faction_num]['faction_name'];?>"><i class="fa fa-times fa-2x"></i></a>
				&nbsp;
				<strong>Faction:</strong>
				<input type="text" id="faction_name_<?php echo $faction_num;?>" name="names[<?php echo $faction_num; ?>][faction_name]" class="edit" value="<?php echo @$factions[$faction_num]['faction_name'];?>"></input>
				<!-- &nbsp;&nbsp;&nbsp;Number of Characters: <?php echo count($faction_characters); ?> / <?php echo CHARACTER_LIMIT; ?>-->
				<br >
				<br >
				<ul id="active_characters_<?php echo $faction_num;?>">
				<?php foreach ($faction_characters as $character_num => $character) :
					$optionA = $optionB = $optionC = $optionD = $optionE = $option0 = '';
					switch ($character['priority']) {
						case '0':
							$option0 = 'selected="selected"';
							break;
						case '1':
							$optionA = 'selected="selected"';
							break;
						case '2':
							$optionB = 'selected="selected"';
							break;
						case '3':
							$optionC = 'selected="selected"';
							break;
						case '4':
							$optionD = 'selected="selected"';
							break;
						default:
					}
					?>
					<li id="character_<?php echo $character['character_id'];?>" data-char-num="<?php echo $character_num;?>-<?php echo $faction_num;?>">
						<a title="Delete Character" class="toggle_character" data-action="delete" data-character-id="<?php echo $character['character_id'];?>" data-character-faction="<?php echo $faction_num;?>" data-character-num="<?php echo $character_num;?>" data-character-name="<?php echo $character['character_name'];?>"><i class="fa fa-times fa-2x delete-character"></i></a>
						&nbsp;&nbsp;Priority:
						<select name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][priority]" id="character_priority_<?php echo $character_num;?>">
							<option value="0" <?php echo $option0;?>>None</option>
							<option value="1" <?php echo $optionA;?>>A</option>
							<option value="2" <?php echo $optionB;?>>B</option>
							<option value="3" <?php echo $optionC;?>>C</option>
							<option value="4" <?php echo $optionD;?>>D</option>
						</select>
						&nbsp;Character:
						<input class="character-names" type="text" name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][character_name]" value="<?php echo $character['character_name'];?>"></input>
						&nbsp;&nbsp;Player:
						<input class="player-names" type="text" name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][player_name]" value="<?php echo $character['player_name'];?>"></input>
						&nbsp;&nbsp;Bio:
						<input class="character-bio" type="text" name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][character_bio]" value="<?php echo $character['character_bio'];?>"></input>
					</li>
				<?php endforeach; ?>
				</ul>
				<ul id="deleted_characters_<?php echo $faction_num;?>">
					<?php if ( ! empty($deleted_characters[$faction_num])) : ?>
					<?php foreach ($deleted_characters[$faction_num] as $character_num => $character) :
					$optionA = $optionB = $optionC = $optionD = $optionE = $option0 = '';
					switch ($character['priority']) {
						case '0':
							$option0 = 'selected="selected"';
							break;
						case '1':
							$optionA = 'selected="selected"';
							break;
						case '2':
							$optionB = 'selected="selected"';
							break;
						case '3':
							$optionC = 'selected="selected"';
							break;
						case '4':
							$optionD = 'selected="selected"';
							break;
						default:
					}
					?>
					<li id="character_<?php echo $character['character_id'];?>">
						<a title="Restore Character" class="toggle_character" data-action="restore" data-character-id="<?php echo $character['character_id'];?>" data-character-faction="<?php echo $faction_num;?>" data-character-num="<?php echo $character_num;?>" data-character-name="<?php echo $character['character_name'];?>"><i class="fa fa-undo fa-lg restore-character"></i></a>
						&nbsp;Character:
						<input disabled class="character-names" type="text" name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][character_name]" value="<?php echo $character['character_name'];?>"></input>
						&nbsp;&nbsp;Priority:
						<select disabled name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][priority]">
							<option value="0" <?php echo $option0;?>>None</option>
							<option value="1" <?php echo $optionA;?>>A</option>
							<option value="2" <?php echo $optionB;?>>B</option>
							<option value="3" <?php echo $optionC;?>>C</option>
							<option value="4" <?php echo $optionD;?>>D</option>
						</select>
						&nbsp;&nbsp;Player:
						<input disabled class="player-names" type="text" name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][player_name]" value="<?php echo $character['player_name'];?>"></input>
						&nbsp;&nbsp;Bio:
						<input disabled class="character-bio" type="text" name="names[<?php echo $faction_num;?>][<?php echo $character_num;?>][character_bio]" value="<?php echo $character['character_bio'];?>"></input>
					</li>
					<?php endforeach ; ?>
					<?php endif; ?>
				</ul>
				</div>
				<?php endforeach; ?>
				<div id="deleted-factions" style="padding: 10px;">
				<?php if ( ! empty($deleted_factions)) : ?>
				<h4>Deleted Factions</h4>
				<?php foreach ($deleted_factions as $faction_num => $faction_characters) : ?>
					<p>
						<a title="Restore Faction" class="restore_faction" id="restore_faction_<?php echo $faction_num;?>" data-faction-id="<?php echo $factions[$faction_num]['faction_id'];?>" data-faction-name="<?php echo $factions[$faction_num]['faction_name'];?>"><i class="fa fa-undo fa-lg"></i></a>
						&nbsp;Faction: <?php echo $factions[$faction_num]['faction_name'];?>
					</p>
				<?php endforeach; ?>
				</div>
				<?php endif; ?>
				</div>
				<br >
				<input type="submit" value="Save Project" id="save-project">
			</form>
		<?php include 'includes/footer.php'; ?>
		</div>
	</div>
	</body>
</html>
