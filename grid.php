<?php 
error_reporting(0);
include 'core/init.php';

protect_page();
$user_id = $_SESSION['user_id'];
$project_id = get_active_project($user_id);
$factions = get_project_factions($project_id);
foreach ($factions as $faction) {
	if ($faction['deleted']) {
		continue;
	}
	if ( ! isset($bearer)) {
		$bearer = $faction['faction_num'];
		$receiver = $faction['faction_num'];
	} else {
		$receiver = $faction['faction_num'];
		break;
	}
}

if ( ! empty($_GET['bearer']) && ! empty($_GET['receiver'])) {
	$bearer = $_GET['bearer'];
	$receiver = $_GET['receiver'];
}

$bearer_id = mysql_result(mysql_query("SELECT `faction_id` FROM `factions` WHERE `project_id`='$project_id' AND `faction_num`='$bearer'"), 0);
$receiver_id = mysql_result(mysql_query("SELECT `faction_id` FROM `factions` WHERE `project_id`='$project_id' AND `faction_num`='$receiver'"), 0);
$bearer_data = array();
$receiver_data = array();

// Get list of characters in faction 1
$bearer_result = mysql_query("SELECT `character_id`, `character_name`, `faction`, `player_name`, `deleted` FROM `characters` WHERE `project_id`='$project_id' && `faction`='$bearer' ORDER BY `character_id`"); 
while($row = mysql_fetch_assoc($bearer_result)){
	$bearer_data[] = $row; 
	//data appears as $bearer_data[0]['character_id']   range is 0 to 11
}

// Get list of characters in faction 2
$receiver_result = mysql_query("SELECT `character_id`, `character_name`, `faction`, `player_name`, `deleted` FROM `characters` WHERE `project_id`='$project_id' && `faction`='$receiver' ORDER BY `character_id`");
while($row = mysql_fetch_assoc($receiver_result)){
	$receiver_data[] = $row; 
	//data appears as $receiver_data[0]['character_id']   range is 0 to 11
}

$q = "SELECT `faction_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_f2f` WHERE (`project_id`='$project_id' && `faction_1_id`='$bearer_id' && `faction_2_id`='$receiver_id') ORDER BY `faction_1_id`";
$result = mysql_query($q) or die(mysql_error());
$f2f_opinion_word = "Neutral";
$f2f_opinion_text = "No opinion";
if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		$f2f_opinion_word = $row['opinion_word'];
		$f2f_opinion_text = $row['opionion_text'];
   }
}

$list = array();
foreach ($bearer_data as $character) {
	$list[] = $character['character_id'];
}

$q = "
	SELECT `character_1_id`, `faction_2_id`, `opinion_word`, `opinion_text`
	FROM `opinions_c2f`
	WHERE `project_id`='$project_id'
	  AND `faction_2_id` = '$receiver_id'
	  AND `character_1_id` IN ('" . implode("','", $list) . "')
	ORDER BY `character_1_id`";
$result = mysql_query($q);
$c2f_data = array();
while ($row = mysql_fetch_assoc($result)) {
	$c2f_data[$row['character_1_id']] = array(
		'opinion_word' => $row['opinion_word'],
		'opinion_text' => $row['opinion_text']
	);
}

$q = "
	SELECT `character_1_id`, `character_2_id`, `opinion_word`, `opinion_text`
	FROM `opinions_c2c`
	WHERE `project_id` = '$project_id'
	  AND `character_1_id` IN ('" . implode("','", $list) . "')
	ORDER BY `character_1_id`
";
$result = mysql_query($q);
$c2c_data = array();
while ($row = mysql_fetch_assoc($result)) {
	$c2c_data[$row['character_1_id']][$row['character_2_id']] = array(
		'opinion_word' => $row['opinion_word'],
		'opinion_text' => $row['opinion_text']
	);
}

$display_data = array();
foreach ($bearer_data as $character) {
	$c2f = (isset($c2f_data[$character['character_id']])) ? $c2f_data[$character['character_id']]['opinion_word'] : $f2f_opinion_word;
	$display_data[$character['character_id']] = array(
		'character' => $character,
		'opposite_faction_opinion' => $c2f
	);
	foreach ($receiver_data as $target) {
		$opinion = $c2f;
		if (isset($c2c_data[$character['character_id']]) && isset($c2c_data[$character['character_id']][$target['character_id']])) {
			$opinion = $c2c_data[$character['character_id']][$target['character_id']]['opinion_word'];
		}
		$display_data[$character['character_id']]['targets'][$target['character_id']] = array('opinion' => $opinion, 'deleted' => $target['deleted']);
	}
}

$bearer_faction = mysql_result(mysql_query("SELECT `faction_name` FROM `factions` WHERE `project_id`='$project_id' && `faction_num`='$bearer'"), 0);
$receiver_faction = mysql_result(mysql_query("SELECT `faction_name` FROM `factions` WHERE `project_id`='$project_id' && `faction_num`='$receiver'"), 0);
$faction_data = get_faction_data($project_id);  //data is used to determine if a faction is deleted

$alpha = array('a','b','c','d','e','f','g','h','i','j','k', 'l','m','n','o'); //used in identifying opinion location
$factionListA = array();
$factionListB = array();
for ($number = 1; $number <= 12; $number++) {
	if (isset($faction_data['faction_id_' . $number])) {
		if ($faction_data['faction_num_' . $number . '_deleted'] == 0) {
			$selected = '';
			if ($number == $bearer) {
				$selected = 'selected="selected"';
			}
			$factionListA[] = '<option ' . $selected . ' value="' . $number . '">' . $faction_data["faction_name_" . $number] . '</option>';
		}
	}
}
for ($number = 1; $number <= 12; $number++) {
	if (isset($faction_data['faction_id_' . $number])) {
		if ($faction_data['faction_num_' . $number . '_deleted'] == 0) {
			$selected = '';
			if ($number == $receiver) {
				$selected = 'selected="selected"';
			}
			$factionListB[] = '<option ' . $selected . ' value="' . $number . '">' . $faction_data["faction_name_" . $number] . '</option>';
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"> -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/primary.css">
		<script src="includes/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="/includes/javascript_grid.js"></script>
		<script type="text/javascript" src="/includes/colorbox.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function(){
				$(".iframe").colorbox();
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
			});
		</script>
	</head>
	<body>
		<div id="container">
			<h1>The Factionizer - Project: <?php echo $activeProject['project_name'];?></h1>
			<ul class="menu">
				<li><a href="index.php">Home</a></li>
				<li><a href="new_project.php">New</a></li>
				<li><a href="load.php">Open</a></li>
				<li><a href="edit_project.php">Edit Project</a></li>
				<li class="selected"><a href="grid.php">Grid</a></li>
				<li><a href="character_card.php">Character Cards</a></li>
				<li><a href="print.php">Print CC's</a></li>
			</ul>
			<div id="grid_container">
				<?php if ( ! empty($factionListA)) : ?>
				<form class="faction_picker" action="">
				How <select class="dropdown" name="bearer">
				<?php
				foreach ($factionListA as $item) {
					echo $item;
				}
				?>
				</select> feels about <select class="dropdown" name="receiver">
				<?php
				foreach ($factionListB as $item) {
					echo $item;
				}

				?>
				</select> &nbsp; &nbsp;
				<input class="submit_button" type="submit" value="Update">
				</form>
				<br /><br /><br /><br /><br />
				<div class="block hidden_visible"></div>
				<div id="columnheader_receiver" class="block faction">
					<a class="iframe" href="character_card.php?faction_overview=<?php echo $receiver_id;?>">Faction: <?php echo $receiver_faction;?></a>
				</div>
				<?php
				$count = 0;
				foreach ($receiver_data as $target) :
					if ($target['deleted']) {
						continue;
					}
					$letter = $alpha[$count];
					$count++;
				?>
					<div class="block <?php echo ($target['deleted']) ? 'hidden' : '';?>" id="columnheader<?php echo $letter;?>"><a class="iframe" href="character_card.php?character=<?php echo $target['character_id'];?>"><?php echo $target['character_name'];?></a></div>
				<?php endforeach; ?>
				<span class="clear"></span>
				<div id="rowheader_receiver" class="block faction"><a class="iframe" href="character_card.php?faction_overview=<?php echo $bearer_id;?>">Faction: <?php echo $bearer_faction;?></a></div>
				<div id="faction2faction" class="block"><a class="iframe" href="input.php?f2f&bearer=<?php echo $bearer_id;?>&receiver=<?php echo $receiver_id;?>"><?php echo $f2f_opinion_word;?></a></div>
				<span class="clear"></span>
				<?php
				$row = 1;
				foreach ($display_data as $source) :
					$count = 0;
					if ($source['character']['deleted']) {
						continue;
					}
					?>
					<div id="rowheader<?php echo $row;?>" class="block">
						<a class="iframe" href="character_card.php?character=<?php echo $source['character']['character_id'];?>"><?php echo $source['character']['character_name'];?></a>
					</div>
					<div id="character_to_faction_<?php echo $row;?>" class="block">
						<a class="iframe" href="input.php?c2f&bearer=<?php echo $source['character']['character_id'];?>&receiver=<?php echo $receiver_id;?>"><?php echo $source['opposite_faction_opinion'];?></a>
					</div>
					<?php foreach ($source['targets'] as $target_id => $target) :
						if ($target['deleted']) {
							//$class = 'hidden';
							continue;
						}
						$letter = $alpha[$count];
						$count++;
						$class = '';
						?>
						<div id="block<?php echo $row . $letter;?>" class="block <?php echo $class;?>">
							<a class="iframe" href="input.php?c2c&bearer=<?php echo $source['character']['character_id'];?>&receiver=<?php echo $target_id;?>"><?php echo $target['opinion'];?></a>
						</div>
					<?php endforeach;
					$row++;
					?>
					<span class="clear"></span>
				<?php endforeach; ?>
				<?php /*  ADD BUTTONS (cut into email) */ ?>
				<div class="columnfaction"></div>
				<?php for ($number = 0; $number <= 12; $number++) {
					$letter = $alpha[$number]; ?>
					<div class="column<?php echo $letter;?>"></div>
					<div class="row<?php echo $number;?>"></div>
				<?php } ?>
				<?php else: ?>
				<p>No factions configured or all factions deleted.</p>
				<a href="edit_project.php">Edit Project</a>
				<?php endif; ?>
				<?php include 'includes/footer.php'; ?>
			</div>
		</div>
	</body>
</html>