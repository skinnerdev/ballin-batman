<?php
/**
 * Returns the opinion of the receiver held by the bearer. If no direct opinion exists,
 * the opinion of the receivers faction held by the bearer is returned. If no direct opinion
 * exists, the opinion of the receivers faction held by the bearer's faction is returned.
 */
function get_character_opinions($type, $project_id, $bearer_id, $receiver_id) {
	if (empty($type) || empty($project_id) || empty($bearer_id) || empty($receiver_id)) {
		return false;
	}
	$return = array(
		'bearer' => array(),
		'receiver' => array(),
		'opinion_word' => 'Neutral',
		'opinion_text' => 'Neutral',
		'from' => '',
		'f2f' => array()
	);
	switch ($type) {
		case 'c2c':
			$return['c2f'] = array();
			$return['c2c'] = array();
			$bearer = get_character_data($project_id, $bearer_id);
			$bearer_faction_id = $bearer['faction']['faction_id'];
			$return['bearer'] = $bearer;
			$receiver = get_character_data($project_id, $receiver_id);
			$receiver_faction_id = $receiver['faction']['faction_id'];
			$return['receiver'] = $receiver;
			break;
		case 'c2f':
			$return['c2f'] = array();
			$bearer = get_character_data($project_id, $bearer_id);
			$receiver = get_faction($receiver_id);
			$bearer_faction_id = $bearer['faction']['faction_id'];
			$receiver_faction_id = $receiver_id;
			$return['bearer'] = $bearer;
			$return['receiver'] = $receiver;
			$bearer_id = $bearer['faction']['faction_id'];
			break;
		case 'f2f':
			$bearer = get_faction($bearer_id);
			$receiver = get_faction($receiver_id);
			$bearer_faction_id = $bearer_id;
			$receiver_faction_id = $receiver_id;
			$return['bearer'] = $bearer;
			$return['receiver'] = $receiver;
			break;
	}
	$result = get_f2f_opinions($project_id, $bearer_faction_id, $receiver_faction_id);
	if ( ! empty($result)) {
		$return['opinion_word'] = (empty($result['opinion_word'])) ? 'Neutral' : $result['opinion_word'];
		$return['opinion_text'] = (empty($result['opinion_text'])) ? 'Neutral' : $result['opinion_text'];
		$return['f2f'] = array(
			'opinion_id' => $result['opinion_id'],
			'opinion_word' => (empty($result['opinion_word'])) ? 'Neutral' : $result['opinion_word'],
			'opinion_text' => (empty($result['opinion_text'])) ? 'Neutral' : $result['opinion_text']
		);
		$return['from'] = 'f2f';
	}

	if ($type != 'f2f') {
		$result = get_c2f_opinions($project_id, $bearer_id, $receiver_faction_id);
		if ( ! empty($result)) {
			$return['opinion_word'] = (empty($result['opinion_word'])) ? 'Neutral' : $result['opinion_word'];
			$return['opinion_text'] = (empty($result['opinion_text'])) ? 'Neutral' : $result['opinion_text'];
			$return['c2f'] = array(
				'opinion_id' => $result['opinion_id'],
				'opinion_word' => (empty($result['opinion_word'])) ? 'Neutral' : $result['opinion_word'],
				'opinion_text' => (empty($result['opinion_text'])) ? 'Neutral' : $result['opinion_text']
			);
			$return['from'] = 'c2f';
		}
		if ($type == 'c2c') {
			$result = get_c2c_opinions($project_id, $bearer_id, $receiver_id);
			if ( ! empty($result)) {
				$return['opinion_word'] = (empty($result['opinion_word'])) ? 'Neutral' : $result['opinion_word'];
				$return['opinion_text'] = (empty($result['opinion_text'])) ? 'Neutral' : $result['opinion_text'];
				$return['c2c'] = array(
					'opinion_id' => $result['opinion_id'],
					'opinion_word' => (empty($result['opinion_word'])) ? 'Neutral' : $result['opinion_word'],
					'opinion_text' => (empty($result['opinion_text'])) ? 'Neutral' : $result['opinion_text']
				);
				$return['from'] = 'c2c';
			}
		}
	}
	return $return;
}

function get_f2f_opinions($project_id, $faction_1_id, $faction_2_id) {
	if (empty($project_id) || empty($faction_1_id) || empty($faction_2_id)) {
		return false;
	}
	$q = "
		SELECT *
		FROM `opinions_f2f`
		WHERE `faction_1_id` = {$faction_1_id}
		  AND `faction_2_id` = {$faction_2_id}
		  AND `project_id` = {$project_id}
		LIMIT 1;
	";
	$result = mysql_query($q);
	if ($result) {
		return mysql_fetch_assoc($result);
	}
	return false;
}

function get_c2f_opinions($project_id, $character_1_id, $faction_2_id) {
	if (empty($project_id) || empty($character_1_id) || empty($faction_2_id)) {
		return false;
	}
	$q = "
		SELECT *
		FROM `opinions_c2f`
		WHERE `character_1_id` = {$character_1_id}
		  AND `faction_2_id` = {$faction_2_id}
		  AND `project_id` = {$project_id}
		LIMIT 1;
	";
	$result = mysql_query($q);
	if ($result) {
		return mysql_fetch_assoc($result);
	}
	return false;
}

function get_c2c_opinions($project_id, $character_1_id, $character_2_id) {
	if (empty($project_id) || empty($character_1_id) || empty($character_2_id)) {
		return false;
	}
	$q = "
		SELECT *
		FROM `opinions_c2c`
		WHERE `character_1_id` = {$character_1_id}
		  AND `character_2_id` = {$character_2_id}
		  AND `project_id` = {$project_id}
		LIMIT 1;
	";
	$result = mysql_query($q);
	if ($result) {
		return mysql_fetch_assoc($result);
	}
	return false;
}

/**
 * Returns details about a character and their faction
 */
function get_character_data($project_id, $character_id) {
	if (empty($character_id)) {
		return false;
	}
	$return = array();
	$character_id = (int)sanitize($character_id);
	$q = "
		SELECT *
		FROM `characters`
		WHERE `character_id` = $character_id
		LIMIT 1;
	";
	$rs = mysql_query($q);
	if ($rs) {
		$result = mysql_fetch_assoc($rs);
		if ( ! empty($result)) {
			$return['character'] = $result;
			$return['faction'] = get_faction_by_num($project_id, $return['character']['faction']);
			return $return;
		}
	}
	return false;
}

function get_faction_by_num($project_id, $faction_num) {
	if (empty($project_id) || empty($faction_num)) {
		return false;
	}
	$q = "
		SELECT *
		FROM `factions`
		WHERE `faction_num` = {$faction_num}
		  AND `project_id` = $project_id
		LIMIT 1;
	";
	$rs = mysql_query($q);
	if ($rs) {
		$result = mysql_fetch_assoc($rs);
		if ( ! empty($result)) {
			return $result;
		}
	}
	return false;
}

function save_opinion($type, $project_id, $bearer_id, $receiver_id, $opinion_word, $opinion_text, $opinion_id = false) {
	if (empty($type) || empty($project_id) || empty($bearer_id) || empty($receiver_id)) {
		return false;
	}
	if ($type != 'c2c' && $type != 'c2f' && $type != 'f2f') {
		return false;
	}
	$opinion_word = sanitize($opinion_word);
	$opinion_text = sanitize($opinion_text);
	if ($opinion_id) {
		$opinion_id = (int)$opinion_id;
		$q = "
			UPDATE `opinions_$type`
			SET `opinion_word`= '$opinion_word', `opinion_text`= '$opinion_text'
			WHERE `opinion_id` = $opinion_id;
		";
	} else {
		$bearer_id = (int)$bearer_id;
		$receiver_id = (int)$receiver_id;
		$project_id = (int)$project_id;
		$column1 = 'character';
		$column2 = 'faction';
		switch ($type) {
			case 'c2c':
				$column2 = 'character';
				break;
			case 'f2f':
				$column1 = 'faction';
				break;
		}
		$q = "
			INSERT INTO `opinions_$type` (`{$column1}_1_id`, `{$column2}_2_id`, `opinion_word`, `opinion_text`, `project_id`)
			VALUES ('$bearer_id', '$receiver_id', '$opinion_word', '$opinion_text', '$project_id');
		";
	}
	if (mysql_query($q)) {
		return true;
	} else {
		return false;
	}
}

function save_project_data($project_id, $save_data) {
	if (empty($project_id) && empty($save_data)) {
		return false;
	}
	foreach ($save_data as $type => $data) {
		switch ($type) {
			case 'project_name':
				foreach ($data as $project_name) {
					$project_name = sanitize($project_name);
					$q = "
						UPDATE `projects`
						SET `project_name` = '$project_name'
						WHERE `project_id` = '$project_id';
					";
					$save = mysql_query($q);
				}
				break;
			case 'factions':
				foreach ($data as $faction_id => $faction_name) {
					$faction_name = sanitize($faction_name);
					$q = "
						UPDATE `factions`
						SET `faction_name` = '$faction_name'
						WHERE `faction_id` = '$faction_id';
					";
					$save = mysql_query($q);					
				}
				break;
			case 'characters':
				foreach ($data as $character_id => $character) {
					if ( ! empty($character['character_name'])) {
						$character_name = sanitize($character['character_name']);
						$q = "
							UPDATE `characters`
							SET `character_name` = '$character_name'
							WHERE `character_id` = '$character_id';
						";
						$save = mysql_query($q);
					}
					if (isset($character['player_name'])) {
						$player_name = sanitize($character['player_name']);
						$q = "
							UPDATE `characters`
							SET `player_name` = '$player_name'
							WHERE `character_id` = '$character_id';
						";
						$save = mysql_query($q);
					}
					if (isset($character['character_bio'])) {
						$character_bio = sanitize($character['character_bio']);
						$q = "
							UPDATE `characters`
							SET `character_bio` = '$character_bio'
							WHERE `character_id` = '$character_id';
						";
						$save = mysql_query($q);
					}
				}
				break;
		}
	}
	return true;
}

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
	$project = get_project($project_id);
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
	$project = get_project($project_id);
	if ($project['user_id'] != $_SESSION['user_id']) {
		return false;
	}
	return array(
		'character' => $character,
		'project' => $project
	);
}

function project_add_faction($project_id) {
	$project = get_project($project_id);
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

function create_project($project_data, $project_name, $user_id, $faction_qty) {
	$project_name = sanitize($project_name);
	mysql_query("INSERT INTO `projects` (`user_id`, `project_name`, `faction_qty`) VALUES ('$user_id', '$project_name', '$faction_qty')");
	$project_id = mysql_result(mysql_query("SELECT `project_id` FROM `projects` WHERE `project_name`='$project_name' and `user_id` = '$user_id'"), 0);
	set_active_project($project_id);
	if ( ! empty($project_data['faction'])) {
		foreach ($project_data['faction'] as $faction_num => $faction_name) {
			mysql_query("INSERT INTO `factions` (`faction_name`, `faction_num`, `project_id`) VALUES ('$faction_name', '$faction_num', '$project_id')");
		}
	}
	return $project_id;
}

function set_active_project($project_id = null) {
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

function get_project_list() {
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

function get_project($id = null) {
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

function create_rand_data($project_id) {
	$project = get_project($project_id);
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