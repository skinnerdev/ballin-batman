<?php
include 'core/init.php';
protect_page();
$user_id = $_SESSION['user_id'];
$alpha = array('a','b','c','d','e','f','g','h','i','j','k', 'l');
$project_id = $activeProject['project_id'];
if (empty($activeProject)) {  //redirects if there's no active project for the user (if they've not created one)
	header("Location: new_project.php");
	exit;
}

if ($project_id == 0) {  //redirects if there's no active project for the user (if they've not created one)
	echo "<script>parent.jQuery.colorbox.close(); parent.location.reload();</script>";
	exit;
}

$character_id = @$_GET['character'];
$character_data = get_character_data($project_id, $character_id);
$characters = get_project_characters($project_id);
$factions = get_project_factions($project_id);
$projects = get_project_list();
if (empty($character_data)) {
	if (empty($factions)) {
		echo "Invalid character id!<script>parent.jQuery.colorbox.close(); parent.location.reload();</script>";
		exit;
	}
	unset($character_id);
	foreach ($factions as $faction) {
		if ( ! $faction['deleted']) {
			foreach ($characters[$faction['faction_num']] as $character) {
				if ( ! $character['deleted']) {
					$character_id = $character['character_id'];
					header("Location: character_card.php?character=" . $character_id);
					exit;
				}
			}
		}
	}
}
$owned = false;
foreach ($projects as $project) {
	if ($character_data['character']['project_id'] == $project['project_id']) {
		$owned = true;
		break;
	}
}
if ( ! $owned) {
	header("Location: new_project.php");
	exit;
}

foreach ($characters as $faction_num => $faction_characters) {
	foreach ($faction_characters as $character_num => $faction_character) {
		$opinion['opinion_word'] = "No character details set.";
		if ($character_data['character']['character_id'] != $faction_character['character_id']) {
			$opinion = get_character_opinions('c2c', $project_id, $character_data['character']['character_id'], $faction_character['character_id']);
		}
		$characters[$faction_num][$character_num]['opinion'] = $opinion;
	}
}
				
if (!isset($_GET['character']) || empty($_GET['character']) == true) {
	$a=1;
	$result = mysql_result(mysql_query("SELECT `character_id` FROM `characters` WHERE `project_id`='$project_id' && `character_number` = '$a' && `faction`='$a'"),0);
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=character_card.php?character=' . $result . '">';
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/character.css">
		<script src="includes/jquery-1.9.0.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/includes/colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".iframe").colorbox();
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
			});
		</script>
	</head>
	<body>
		<div id="container">
			<div id="grid_container">
				<h2>Character Name: <?php echo $character_data['character']['character_name'];?></h2>
				<h4>Player Name: <?php echo $character_data['character']['player_name'];?></h4>
				<h4>Faction: <?php echo $character_data['faction']['faction_name'];?></h4>
				<h4>Priority: <?php echo $character_data['character']['priority_label'];?></h4>
				<h4>Bio:</h4>
				<p>
					<?php echo $character_data['character']['character_bio'];?>
				</p>
				<?php
				foreach ($characters as $faction_num => $faction_characters) {
					if ($factions[$faction_num]['deleted']) {
						continue;
					}
					$my_faction = '';
					if ($factions[$faction_num]['faction_id'] == $character_data['faction']['faction_id']) {
						$my_faction = ' (My Faction) ';
					}
					?>
					<span class="clear"></span>
					<h3>Faction: <?php echo $factions[$faction_num]['faction_name'] . $my_faction;?></h3>
					<span class="clear"></span>
					<?php
					foreach ($faction_characters as $character_num => $faction_character) {
						if ($faction_character['deleted'] || $faction_character['character_id'] == $character_data['character']['character_id']) {
							continue;
						}
						?>
						<div class="block">
							<a href="character_card.php?character=<?php echo $faction_character['character_id'];?>"><?php echo $faction_character['character_name'];?></a>
						</div>
						<?php
					}
					?>
					<span class="clear"></span>
					<?php
					$count = 0;
					foreach ($faction_characters as $character_num => $faction_character) {
						if ($faction_character['deleted'] || $faction_character['character_id'] == $character_data['character']['character_id']) {
							continue;
						}
						$letter = $alpha[$count];
						?>
						<div id="receiver_<?php echo ($count+1) . $letter;?>" class="block">
							<a class="iframe" href="input.php?type=c2c&bearer=<?php echo $character_data['character']['character_id'];?>&receiver=<?php echo $faction_character['character_id'];?>"><?php echo $faction_character['opinion']['opinion_word'];?></a>
						</div>
						<?php
						$count++;
					}
					?>
					<span class="clear"></span>
					<?php
					foreach ($faction_characters as $character_num => $faction_character) {
						if ($faction_character['deleted'] || $faction_character['character_id'] == $character_data['character']['character_id']) {
							continue;
						}
						?>
						<strong><?php echo $faction_character['character_name'];?></strong> - <?php echo $faction_character['opinion']['opinion_word'];?> - <?php echo $faction_character['opinion']['opinion_text'];?>
						<br>
						<?php
						$count++;
					}
				}
				?>
				<span class="clear"></span>
				<?php include 'includes/footer.php'; ?>
			</div>
		</div>
	</body>
</html>