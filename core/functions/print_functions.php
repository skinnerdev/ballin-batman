<?php 
function get_all_data($project_id) {
	include 'core/init.php';
	global $user_id, $project_id;
	include 'includes/check_connect.php';
	protect_page();
	$project_id = mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0);
	global $character_data, $f2f_opinions, $c2f_opinions, $c2c_opinions, $character_id_list;
	$character_data=array();
	
	// Get the ID & names of all chars, player names
	
	$faction_num=1;
	while ($faction_num<=12){
		$character_num=1;
		while ($character_num<=12) {
			$character_id = mysql_result(mysql_query("SELECT `character_id` FROM `characters` WHERE `project_id` = '$project_id' && `faction`='$faction_num' && `character_number`='$character_num'"),0);
			$character_name = mysql_result(mysql_query("SELECT `character_name` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
			$player_name = mysql_result(mysql_query("SELECT `player_name` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
			$character_data[$faction_num . "_" . $character_num]['character_name'] = $character_name;
			$character_data[$faction_num . "_" . $character_num]['player_name'] = $player_name;
			$character_data[$faction_num . "_" . $character_num]['character_id'] = $character_id;
			$character_num++;
		}
		$faction_num++;
	}
	
	global $faction_data;
	$faction_data = array();
	$sql = "SELECT `faction_id`,`faction_name`,`faction_num`, `deleted` FROM `factions` WHERE `project_id` = '$project_id' ORDER BY `faction_id`";
	$result = mysql_query($sql);
	$num=mysql_numrows($result);  //counts the rows
	$i=0;
	while ($i < $num) {
		$var = $i+1;
		$faction_data['faction_id_' . $var] = mysql_result($result,$i);  //id of faction based on number
		$faction_data['faction_name_' . $var] = mysql_result($result,$i,1);  //name of faction
		$faction_data['faction_num_' . $var . '_deleted'] = mysql_result($result,$i,3); //deleted or not
		$i++;
	}
			// $faction_data['faction_id_' . $number]
			// $faction_data['faction_name_' . $number]
			// $faction_data['faction_num_' . $number]
			// $faction_data['faction_num_' . $number . '_deleted']
	
	
	//f2f data
	$result = mysql_query("SELECT `faction_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_f2f` WHERE (`project_id`='$project_id') ORDER BY `faction_1_id`") or die(mysql_error());
	$f2f_opinions = array();
	if (mysql_num_rows($result)==0) {
		$f2f_opinion_word = "Neutral";
		$f2f_opinion_text = "No opinion";
	} else {
		$number=1;
		while ($row = mysql_fetch_assoc($result)) {
			foreach ($row as $key => $value) {
				if ($key=='faction_1_id') {
					$first_faction = $value;
				} else if ($key=='faction_2_id') {
					$second_faction = $value;
				} else if ($key !== 'faction_1_id' && $key !== 'faction_2_id') {
					$f2f_opinions[$first_faction][$second_faction][$key] = $value;///////////issue
				} else echo "error";
			}
			$number++;
		}
		$f2f_opinions['empty'] = false;
	}
	
	
	//c2f data
	$c2f_opinions = array();
	$result = mysql_query("SELECT `character_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2f` WHERE `project_id`='$project_id' ORDER BY `character_1_id`");
	if (mysql_num_rows($result)==0) {
		$c2f_opinions['empty'] = true;
	} else {
		while ($row = mysql_fetch_assoc($result)) {
			foreach ($row as $key => $value) {
				if ($key=='character_1_id') {
					$c2f_opinions[1] = $value;
				} else if ($key=='faction_2_id') {
					$c2f_opinions[2] = $value;
				} else if ($key!='character_1_id' && $key!='faction_2_id' ) {
					$c2f_opinions[$c2f_opinions[1]][$c2f_opinions[2]][$key] = $value;
				} else echo "error";
			}
		}
		unset($c2f_opinions[0]);
		unset($c2f_opinions[1]);
		unset($c2f_opinions[2]);
		$c2f_opinions['empty'] = false;
	}
	
	
	//c2c opinions
	$c2c_opinions = array();
	$result = mysql_query("SELECT `character_1_id`, `character_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2c` WHERE `project_id` = '$project_id' ORDER BY `character_1_id`");
	if (mysql_num_rows($result)==0) {
		$c2c_opinions['empty'] = true;
	} else {
		while ($row = mysql_fetch_assoc($result)) {
			foreach ($row as $key => $value) {
				if ($key=='character_1_id') {
					$c2c_opinions[1] = $value;
				} else if ($key=='character_2_id') {
					$c2c_opinions[2] = $value;
				} else if ($key!='character_1_id' && $key!='character_2_id' ) {
					$c2c_opinions[$c2c_opinions[1]][$c2c_opinions[2]][$key] = $value;
				} else echo "error";
			}
		}
		unset($c2c_opinions[0]);
		unset($c2c_opinions[1]);
		unset($c2c_opinions[2]);
		$c2c_opinions['empty'] = false;
	}
	
	
	//A list of all character ID's in the project
	$character_id_list = array();
	$number=0;
	$result = mysql_query("SELECT `character_id` FROM `characters` WHERE `project_id`='$project_id' ORDER BY `character_id`");
	while($row = mysql_fetch_array($result)){
		$character_id_list[] = $row;  //$character_id_list[0]['character_id']; //range is 0 to 143
		unset($character_id_list[$number][0]);
		$number++;
	}
	
	return $c2c_opinions;
	
	
}
?>