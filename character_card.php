<?php include 'core/init.php';?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/character.css">
		<script src="includes/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="/includes/colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".iframe").colorbox();
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
			});
		</script>
	</head>
	<body>
		<?php
		protect_page();
		$user_id = $_SESSION['user_id'];
		$alpha = array('a','b','c','d','e','f','g','h','i','j','k', 'l'); //used in identifying opinion location
		$project_id = mysql_result(mysql_query("SELECT `active_project` FROM `users` WHERE `user_id`='$user_id'"), 0);
		echo '<div id="container">';
			/*echo '<h1>The Factionizer</h1>';
			echo '<ul class="menu">';
				echo '<li><a href="index.php">Home</a></li>';
				echo '<li><a href="new_project.php">New</a></li>';
				echo '<li><a href="load.php">Open</a></li>';
				echo '<li><a href="edit_project.php">Edit Project</a></li>';
				echo '<li><a href="grid.php">Grid</a></li>';
				echo '<li class="selected"><a href="character_card.php">Character Cards</a></li>';
			echo '</ul>';*/
			echo '<div id="grid_container">';
				$character_data = get_character_data($project_id);
								
				if (!isset($_GET['character']) || empty($_GET['character']) == true) {
					$a=1;
					$result = mysql_result(mysql_query("SELECT `character_id` FROM `characters` WHERE `project_id`='$project_id' && `character_number` = '$a' && `faction`='$a'"),0);
					echo '<meta HTTP-EQUIV="REFRESH" content="0; url=character_card.php?character=' . $result . '">';
					exit();
				}
			
				$character_id = $_GET['character'];
				$faction_num = mysql_result(mysql_query("SELECT `faction` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
				$character_name = mysql_result(mysql_query("SELECT `character_name` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
				$player_name = mysql_result(mysql_query("SELECT `player_name` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
				
				$faction_data = get_faction_data($project_id); //data includes: 
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
				$result = mysql_query("SELECT `character_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2f` WHERE `project_id`='$project_id' && `character_1_id` = '$character_id' ORDER BY `character_1_id`");
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
				$result = mysql_query("SELECT `character_1_id`, `character_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2c` WHERE `project_id` = '$project_id' AND `character_1_id` = '$character_id' ORDER BY `character_1_id`");
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
				
				//  SELF History/Description
				
				//$self_opinion_word = $c2c_opinions[$character_id][$character_id]['opinion_word'];
				if (isset($c2c_opinions[$character_id][$character_id]['opinion_text'])) {
					$self_opinion_text = $c2c_opinions[$character_id][$character_id]['opinion_text'];
				} else {
					$self_opinion_text = "No character details set.";
				}
				
				echo "<h2>Character Name: " . $character_name . "<h2>";
				echo "<h2>Player Name: " . $player_name . "<h2>";
				echo "<h2>Faction: " . $faction_data['faction_name_' . $faction_num] . "<h2>";
				//echo "<h3>General Opinion of Myself: " . $self_opinion_word . "<h3>";
				
				echo "<h3>Description: " . $self_opinion_text . "<h3>";
				

				// OPINION BLOCK AND TEXT
				$number=0;
				$number1=0;
				$total=0;
				while ($number<12){
				
					//check for deleted faction
					if ($faction_data['faction_num_' . ($number+1) . '_deleted'] == 0) {
					
						//Determines if the active faction is labelled with "My Faction"
					
						$faction_loop=0;
						$my_faction = "";
						while ($faction_loop<12) {
							if ($character_id == $character_id_list[($number*12+$faction_loop)]['character_id'])
								$my_faction = " (My Faction) ";
							$faction_loop++;
						}
						
						// Title for each opinion area
						
						echo "<br><h2>Faction: " . $faction_data['faction_name_' . ($number+1)] . $my_faction . "</h2>"; 
						
						echo '<span class="clear"></span>'; //clears floats (line return)
						while ($number1<12){
							//determine opinion word & text
							if (($c2c_opinions['empty'] == true) || (!isset($c2c_opinions[$character_id][$character_id_list[$total]['character_id']]))) {
								//if it's empty or not set, check the c2f opinion
								if (($c2f_opinions['empty'] == true) || (!isset($c2f_opinions[$character_id][$number]))) {
									//if c2f is empty or not set, set it to the f2f opinion
									$faction_1_id = mysql_result(mysql_query("SELECT `faction_id` FROM `factions` WHERE `project_id` = '$project_id' && `faction_num`='$faction_num'"),0);
									$faction_2_num = $number+1;
									$faction_2_id = mysql_result(mysql_query("SELECT `faction_id` FROM `factions` WHERE `project_id` = '$project_id' && `faction_num`='$faction_2_num'"),0);
									if (isset($f2f_opinions[$faction_1_id][$faction_2_id]['opinion_word'])) {
										$opinions[$number][$number1]['opinion_word'] = $f2f_opinions[$faction_1_id][$faction_2_id]['opinion_word'];
										$opinions[$number][$number1]['opinion_text'] = $f2f_opinions[$faction_1_id][$faction_2_id]['opinion_word'];
									} else {
										$opinions[$number][$number1]['opinion_word'] = "Neutral";
										$opinions[$number][$number1]['opinion_text'] = "No Opinion";
									}
								} else {
									//sets it to the c2f opinion
									$opinions[$number][$number1]['opinion_word'] = $c2f_opinions[$character_id][$number]['opinion_word'];
									$opinions[$number][$number1]['opinion_text'] = $c2f_opinions[$character_id][$number]['opinion_text'];
								}
							} else {
								//sets it to the c2c opinion
								$opinions[$number][$number1]['opinion_word'] = $c2c_opinions[$character_id][$character_id_list[$total]['character_id']]['opinion_word'];
								$opinions[$number][$number1]['opinion_text'] = $c2c_opinions[$character_id][$character_id_list[$total]['character_id']]['opinion_text'];
							}
						
							// Name of Character
							if ($number1==0) {
								$name_loop=0;
								while ($name_loop<12) {
									if (($character_data['faction_' . ($number+1) . '_character_' . ($name_loop+1). '_deleted'] == false) && ($character_id !== $character_id_list[($total + $name_loop)]['character_id'])) {
										echo '<div  class="block"><a href="character_card.php?character=' . $character_id_list[($total+$name_loop)]['character_id'] . '">' . $character_data['faction_' . ($number+1) . '_character_' . ($name_loop+1) . '_name'] . '</a></div>'; // name block
									} else {
										//echo '<div class="block hidden"><a href="character_card.php?character=' . $character_id_list[($total+$name_loop)]['character_id'] . '">' . $character_data['faction_' . ($number+1) . '_character_' . ($name_loop+1) . '_name'] . '</a></div>';  //hidden name block
									}
									$name_loop++;
								}
								echo '<span class="clear"></span>'; //clears floats (line return)
								$name_loop=0;
								
							}
							//echo $character_id_list[$total]['character_id'];
							//exit();
							// OPINION BLOCK
							if (($character_data['faction_' . ($number+1) . '_character_' . ($number1+1). '_deleted'] == false) && ($character_id !== $character_id_list[$total]['character_id'])) {
								$letter = $alpha[$number1];
								echo '<div id="receiver_' . ($number+1) . $letter . '" class="block"><a class="iframe" href="input.php?bearer=' . $character_id . '&receiver=' . $character_id_list[$total]['character_id'] . '">' . $opinions[$number][$number1]['opinion_word'] . '</a></div>'; // opinion block
							} else {
								//echo '<div id="receiver_' . ($number+1) . $letter . '" class="block hidden"><a class="iframe" href="input.php?bearer=' . $character_id . '&receiver=' . $character_id_list[$total]['character_id'] . '">' . $opinions[$number][$number1]['opinion_word'] . '</a></div>'; // hidden opinion block
							}
							$total++;
							$number1++;
						}
						echo '<span class="clear"></span>'; //clears floats (line return)
						
						// Text Area 
						
						$number1=0;
						while ($number1<12){
							if (($character_data['faction_' . ($number+1) . '_character_' . ($number1+1). '_deleted'] == false) && ($character_id !== $character_id_list[($total-12+$number1)]['character_id'])) {
								echo "<strong>" . $character_data['faction_' . ($number+1) . '_character_' . ($number1+1) . '_name'] . "</strong> - " . $opinions[$number][$number1]['opinion_word'] . " - " . $opinions[$number][$number1]['opinion_text'] . "<br>"; // each opinion's opinion text
							}
							$number1++;
						}
					}
					$number++;
					$number1=0;
					echo '<span class="clear"></span>'; //clears floats (line return)
				}
				?>
			</div>
		</div>
	</body>
</html>
<?php 
include 'includes/overall/overall_footer.php'; ?>