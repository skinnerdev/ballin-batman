<?php

function update_names($project_id, $project_name, $faction_names, $character_names, $player_names) {
	if (isset($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
		$project_id = mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0); //sets the project ID
	
		mysql_query("UPDATE `projects` SET `project_name` = '$project_name' WHERE `project_id` = '$project_id'");
		$faction_data = get_faction_data($project_id);
		$character_data = get_character_data($project_id);
		$faction_loop = 1;
		$character_loop = 1;
		while ($faction_loop <=12) {
			if ($faction_data['faction_num_' . $faction_loop . '_deleted'] == 0) {
				//update faction names
				$faction_name = $faction_names['faction_name_' . $faction_loop];
				mysql_query("UPDATE `factions` SET `faction_name` = '$faction_name' WHERE `project_id` = '$project_id' AND `faction_num` = '$faction_loop'");
				while ($character_loop <= 12) {
					if ($character_data['faction_' . $faction_loop . '_character_' . $character_loop . '_deleted'] == 0) {
						//update character names
						$character_name = $character_names['faction_' . $faction_loop . '_character_' . $character_loop . '_name'];
						mysql_query("UPDATE `characters` SET `character_name` = '$character_name' WHERE `project_id` = '$project_id' AND `faction` = '$faction_loop' AND `character_number` = '$character_loop'");
						//update player names
						$player_name = $player_names['faction_' . $faction_loop . '_character_' . $character_loop . '_player'];
						//echo "character id = " . $character_id . "<br>";
						//if ($character_loop == 12 && $faction_loop == 12) {exit();}
						mysql_query("UPDATE `characters` SET `player_name` = '$player_name' WHERE `project_id` = '$project_id' AND `faction` = '$faction_loop' AND `character_number` = '$character_loop'");
					}
					$character_loop++;
				}
			}
			$faction_loop++;
			$character_loop = 1;
		}
	}
}

// function delete_faction($faction_num, $faction_name) {
// 	if (isset($_SESSION['user_id'])) {
// 		$user_id = $_SESSION['user_id'];
// 		$project_id = mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0);
		
// 		mysql_query("UPDATE `factions` SET `deleted` = 1 WHERE `project_id` = '$project_id' AND `faction_num` = '$faction_num'");
// 	}
// }

// function delete_character($faction_num, $character_num) {
// 	if (isset($_SESSION['user_id'])) {
// 		$user_id = $_SESSION['user_id'];
// 		$project_id = mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0);

// 		$character_data = get_character_data($project_id);
// 		$character_id = $character_data['faction_' . $faction_num . '_character_' . $character_num . '_id'];
		
// 		mysql_query("UPDATE `characters` SET `deleted` = 1 WHERE `project_id` = '$project_id' AND `faction` = '$faction_num' AND `character_id` = '$character_id'");

// 	}
// }

function delete_faction_by_id($faction_id) {
	$faction_id = sanitize($faction_id);
	$faction = get_faction($faction_id);
	if ( ! empty($faction) && ! $faction['faction']['deleted']) {
		return (mysql_query("UPDATE `factions` SET `deleted` = 1 WHERE `faction_id` = " . $faction_id)) ? true : false;
	}
	return false;
}

function restore_faction_by_id($faction_id) {
	$faction_id = sanitize($faction_id);
	$faction = get_faction($faction_id);
	if ( ! empty($faction) && $faction['faction']['deleted']) {
		return (mysql_query("UPDATE `factions` SET `deleted` = 0 WHERE `faction_id` = " . $faction_id)) ? true : false;
	}
	return false;
}

function get_faction($faction_id) {
	$faction_id = sanitize((int)$faction_id);
	$q = "
		SELECT * from `factions` where `faction_id` = $faction_id;
	";
	$faction = mysql_fetch_assoc(mysql_query($q));
	$project_id = $faction['project_id'];
	$project = getProject($project_id);
	if ($project['user_id'] != $_SESSION['user_id']) {
		return false;
	}
	return array(
		'faction' => $faction,
		'project' => $project
	);
}

function delete_character_by_id($character_id) {
	$character_id = sanitize((int)$character_id);
	$character = get_character($character_id);
	if ( ! empty($character) && ! $character['character']['deleted']) {
		return (mysql_query("UPDATE `characters` SET `deleted` = 1 WHERE `character_id` = $character_id;")) ? true : false;
	}
	return false;
}

function restore_character_by_id($character_id) {
	$character_id = sanitize((int)$character_id);
	$character = get_character($character_id);
	if ( ! empty($character) && $character['character']['deleted']) {
		return (mysql_query("UPDATE `characters` SET `deleted` = 0 WHERE `character_id` = $character_id;")) ? true : false;
	}
	return false;
}

function get_character($character_id) {
	$character_id = sanitize((int)$character_id);
	$q = "
		SELECT * FROM `characters` WHERE `character_id` = $character_id;
	";
	$character = mysql_fetch_assoc(mysql_query($q));
	$project_id = $character['project_id'];
	$project = getProject($project_id);
	if ($project['user_id'] != $_SESSION['user_id']) {
		return false;
	}
	return array(
		'character' => $character,
		'project' => $project
	);
}


function get_project_data($project_id) {
	$project_data = array();
	$sql = "SELECT `project_name` FROM `projects` WHERE `project_id` = '$project_id'";
	$result = mysql_query($sql);
	$num=mysql_numrows($result);  //counts the rows
	$i=0;
	while ($i < $num) {
		$var = $i+1;
		$project_data['project_name_' . $var] = mysql_result($result,$i);
		if ($i > 1) {echo 'Possible error: Multiple projects';}
		$i++;
	}
	
	return $project_data;
}


function get_faction_data($project_id) {
	$faction_data = array();
	$sql = "SELECT `faction_id`,`faction_name`,`faction_num`, `deleted` FROM `factions` WHERE `project_id` = '$project_id' ORDER BY `faction_id`";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
	$data[] = $row; 
	//data appears as $bearer_data[0]['character_id']   range is 0 to 11
	}
	$num=mysql_numrows($result);  //counts the rows
	$i=0;
	while ($i < $num) {
		$var = $i+1;
		$faction_data['faction_id_' . $var] = mysql_result($result,$i);
		$faction_data['faction_name_' . $var] = mysql_result($result,$i,1);
		$faction_data['faction_num_' . $var] = mysql_result($result,$i,2);
		$faction_data['faction_num_' . $var . '_deleted'] = mysql_result($result,$i,3);
		//if ($i == 0) {print_r($faction_data);}
		$i++;
	}
	$faction_data['faction_qty'] = $i;
	return $faction_data;
}

function get_character_data($project_id){
	$sql = "SELECT `character_id`, `character_name`, `faction`, `player_name`, `deleted` FROM `characters` WHERE `project_id` = '$project_id' ORDER BY `character_id`";
	$result = mysql_query($sql);
	$num=mysql_numrows($result);  //counts the rows
	$i=0;
	$is_first_char = 1;
	$character_num_in_faction = 0;
	$character_data = array();
	$deleted_qty = 0;
	while ($i < $num) {
		$faction_num = mysql_result($result,$i,2);
		if ($faction_num == $is_first_char) {
			$character_num_in_faction++;
			$character_data['faction_' . $faction_num . '_character_' . $character_num_in_faction. '_deleted'] = mysql_result($result,$i,4);  			
			if ($character_data['faction_' . $faction_num . '_character_' . $character_num_in_faction . '_deleted'] == true) {
				$deleted_qty++;
			}
			
			
		} else {
			$faction_num = $faction_num-1;
			$character_num_in_faction = $character_num_in_faction - $deleted_qty;
			$character_data['faction_' . $faction_num . '_char_qty'] = $character_num_in_faction;
			$faction_num++;
			$character_num_in_faction = 1;
			$is_first_char++;
		}
		
		
		$character_data['faction_' . $faction_num . '_char_qty'] = $character_num_in_faction;
		
		$character_data['faction_' . $faction_num . '_character_' . $character_num_in_faction. '_id'] = mysql_result($result,$i);
		$character_data['faction_' . $faction_num . '_character_' . $character_num_in_faction . '_name'] = mysql_result($result,$i,1);
		$character_data['faction_' . $faction_num . '_character_' . $character_num_in_faction . '_player'] = mysql_result($result,$i,3);
		$character_data['faction_' . $faction_num . '_character_' . $character_num_in_faction. '_deleted'] = mysql_result($result,$i,4);
		$i++;

	} 
	$character_data['character_qty'] = $i;  //total chars in this project
	return $character_data;
}


function get_f2f_opinions($project_id, $faction_1_id, $faction_2_id){

}

function get_c2f_opinions($project_id, $character_1_id, $faction_2_id){
}

function get_c2c_opinions($project_id, $character_1_id, $character_2_id){
}


function create_project($project_data, $project_name, $user_id, $faction_qty) {
	$project_name = sanitize($project_name);
	mysql_query("INSERT INTO `projects` (`user_id`, `project_name`, `faction_qty`) VALUES ('$user_id', '$project_name', '$faction_qty')");
	$project_id = mysql_result(mysql_query("SELECT `project_id` FROM `projects` WHERE `project_name`='$project_name' and `user_id` = '$user_id'"), 0);
	setActiveProject($project_id);
	if ( ! empty($project_data['faction'])) {
		foreach ($project_data['faction'] as $faction_num => $faction_name) {
			mysql_query("INSERT INTO `factions` (`faction_name`, `faction_num`, `project_id`) VALUES ('$faction_name', '$faction_num', '$project_id')");
		}
	}
	return $project_id;
}

function create_rand_data($project_id) {
	$project = getProject($project_id);
	$name_limit = $project['faction_qty'] * CHARACTER_LIMIT;
	$rand_names = get_random_names($name_limit);
	$character_num = 1;
	$faction_num = 1;
	foreach ($rand_names as $name) {
		if ($character_num > CHARACTER_LIMIT) {
			$character_num = 1;
			$faction_num++;
		}
		mysql_query("INSERT INTO `characters` (`character_name`,`faction`,`character_number`,`project_id`) VALUES ('$name', '$faction_num', '$character_num', '$project_id')");
		$character_num++;
	}
}

function get_random_names($limit) {
	$fileName = "names.csv";
	$csvData = file_get_contents($fileName); 
	$csvDelim = ","; 
	$data = array_chunk(str_getcsv($csvData, $csvDelim),1);
	$name_list = array();
	while (count($name_list) < $limit) {
		$point = rand(0, 3341);
		$random_name = $data[$point][0];
		$name_list[$random_name] = $random_name; // All unique names
	}
	return array_values($name_list);
}

function update_rand_names($project_id) {
	$fileName = "names.csv";
	$csvData = file_get_contents($fileName); 
	$csvDelim = ","; 
	$data = array_chunk(str_getcsv($csvData, $csvDelim),1); 
	$rand_names = array();
	$num_names=0;
	while ($num_names<144):
		$i=rand(0, 3341);
		$rand_name = $data[$i][0];
		$rand_names[$num_names]=$rand_name;
		$num_names++;
	endwhile;
	$i=0;
	while ($i<144) {
		$faction = (floor($i/12)+1);
		$character_num = (($i+1) % 12);
		if ($character_num==0) {
			$character_num = 12;
		}
		$random_name = $rand_names[$i];
		if ($i == 0) {
			$character_id = mysql_result(mysql_query("SELECT `character_id` FROM `characters` WHERE  `project_id` = '$project_id' AND `faction` = 1 AND `character_number` = 1"),0);
		} else $character_id++;
		if (mysql_result(mysql_query("SELECT `character_name` FROM `characters` WHERE `faction`='$faction' AND `character_number`='$character_num' AND `project_id`='$project_id'"),0) == "") {
			mysql_query("UPDATE `characters` SET `character_name`='$random_name' WHERE `faction`='$faction' AND `character_number`='$character_num' AND `project_id`='$project_id'");
		}
		$i++;
	}
}

function getProject($id = null) {
	if (empty($id)) {
		return false;
	}
	$id = sanitize($id);
	$sql = "SELECT * FROM `projects` WHERE `project_id` = '$id' LIMIT 1;";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
		if ( ! empty($row)) {
			return $row;
		}
	}
	return false;
}

function getProjectList() {
	$q = "
		SELECT * FROM `projects` WHERE `user_id` = " . $_SESSION['user_id'] .";
	";
	$result = mysql_query($q);
	$projects = array();
	while ($row = mysql_fetch_assoc($result)) {
		$projects[] = $row;
	}
	return $projects;
}

function setActiveProject($project_id = null) {
	if (empty($project_id)) {
		return false;
	}
	$project_id = sanitize($project_id);
	// Check to make sure current user owns project
	$q = "
		SELECT * FROM `projects` WHERE `project_id` = '$project_id' and `user_id` = " . $_SESSION['user_id'] . " LIMIT 1;
	";
	$result = mysql_query($q);
	$project = mysql_fetch_assoc($result);
	if (empty($project)) {
		return false;
	}
	$q = "
		UPDATE `users` SET `active_project` = '$project_id' WHERE `user_id` = " . $_SESSION['user_id'] . ";
	";
	$result = mysql_query($q);
	if ($result) {
		return true;
	} else {
		return false;
	}
}

function get_active_project($user_id) {
	return mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0);
}

function get_project_factions($project_id) {
	$project_id = (int)$project_id;
	$q = "
		SELECT * FROM `factions` WHERE `project_id` = $project_id ORDER BY `faction_num` ASC;
	";
	$result = mysql_query($q);
	$factions = array();
	while ($row = mysql_fetch_assoc($result)) {
		$factions[$row['faction_num']] = $row;
	}
	return $factions;
}

function get_project_characters($project_id) {
	$project_id = (int)$project_id;
	$q = "
		SELECT * FROM `characters` WHERE `project_id` = $project_id ORDER BY `faction` ASC, `character_number` ASC;
	";
	$result = mysql_query($q);
	$characters = array();
	while ($row = mysql_fetch_assoc($result)) {
		$characters[$row['faction']][$row['character_number']] = $row;
	}
	return $characters;
}

function project_add_faction($project_id) {
	$project = getProject($project_id);
	if ($project['user_id'] != $_SESSION['user_id']) {
		return false;
	}
	if ($project['faction_qty'] == FACTION_LIMIT) {
		return false;
	}
	$newQuantity = $project['faction_qty'] + 1;
	$q = "
		UPDATE `projects` SET `faction_qty` = $newQuantity WHERE `project_id` = $project_id;
	";
	if (mysql_query($q)) {
		$q = "
			SELECT `faction_num` FROM `factions` WHERE `project_id` = '$project_id' ORDER BY `faction_num` DESC LIMIT 1;
		";
		$last_faction_num = mysql_result(mysql_query($q), 0);
		$faction_num = $last_faction_num + 1;
		$created = date("Y-m-d g:i:s a");
		$q = "
			INSERT INTO `factions` (`faction_name`, `faction_num`, `project_id`, `deleted`) VALUES ('New Faction - ($created)', '$faction_num', '$project_id', 0);
		";
		if (mysql_query($q)) {
			$rand_names = get_random_names(CHARACTER_LIMIT);
			$character_num = 1;
			foreach ($rand_names as $name) {
				mysql_query("INSERT INTO `characters` (`character_name`,`faction`,`character_number`,`project_id`) VALUES ('$name', '$faction_num', '$character_num', '$project_id');");
				$character_num++;
			}
			return true;
		}
	}
	return false;
}