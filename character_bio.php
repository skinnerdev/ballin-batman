<?php
include 'core/init.php';
protect_page();

$user_id = $_SESSION['user_id'];
$project_id = $activeProject['project_id'];
$error = false;
if ( ! empty($_GET['character'])) {
	$character_id = $_GET['character'];
	$character = get_character($character_id);
	if ($character['project']['user_id'] != $user_id || $character['project']['project_id'] != $project_id) {
		$error = "This character is not part of this project";
	}
}
if ( ! $error && ! empty($_POST['data'])) {
	$save_data = array('characters' => array($character_id => array()));
	foreach ($_POST['data'] as $key => $value) {
		if (isset($character['character'][$key])) {
			if (trim($value) != trim($character['character'][$key])) {
				$save_data['characters'][$character_id][$key] = $value;
			}
		}
	}
	if ( ! empty($save_data)) {
		if (save_project_data($project_id, $save_data)) {
			$saveMessage = "Save Succssful!";
		} else {
			$saveMessage = "There was a problem saving the data.";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/input.css">
	<script src="includes/jquery-1.9.0.min.js"></script>
	<script type="text/javascript" src="/includes/colorbox.js"></script>
	<style>
	#character-bio {
		list-style: none;
		margin-left: -35px;
	}
	#character-bio label {
		width: 75px;
	}
	</style>
</head>
<body>
	<div class="container-fluid" id="inputcontainer">
		<?php if ( ! empty($saveMessage)) {
			echo "<h2>$saveMessage</h2>";
			echo "<script>parent.jQuery.colorbox.close(); parent.location.reload();</script></div></body></html>";
			exit;
		}?>
		<?php if ( ! empty($error)) {
			echo "<h2>$error</h2>";
			echo "<script>parent.jQuery.colorbox.close(); parent.location.reload();</script></div></body></html>";
			exit;
		}?>
		<div class="row">
			<div class="col-md-12">
				<h2>Character info for <?php echo $character['character']['character_name'];?></h2>
				<form autocomplete="off" enctype="multipart/form-data" action="character_bio.php?character=<?php echo $character['character']['character_id'];?>" novalidate="" name="input" method="post">
					<ul id="character-bio">
						<li>
							<label for="data[priority]">Priority:</label>
							<select name="data[priority]">
								<?php
								$optionA = $optionB = $optionC = $optionD = $optionE = $option0 = '';
								switch ($character['character']['priority']) {
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
								<option value="0" <?php echo $option0;?>>None</option>
								<option value="1" <?php echo $optionA;?>>A</option>
								<option value="2" <?php echo $optionB;?>>B</option>
								<option value="3" <?php echo $optionC;?>>C</option>
								<option value="4" <?php echo $optionD;?>>D</option>
							</select>
						</li>
						<li>
							<label for="data[character_name">Character:</label>
							<input type="text" name="data[character_name]" value="<?php echo $character['character']['character_name'];?>"></input>
						</li>
						<li>
							<label for="data[player_name]">Player:</label>
							<input type="text" name="data[player_name]" value="<?php echo $character['character']['player_name'];?>"></input>
						</li>
						<li>
							<label for="data[character_bio]">Bio:</label><br >
							<textarea name="data[character_bio]" rows="10" cols="50"><?php echo $character['character']['character_bio'];?></textarea>
						</li>
						<li>
							<button type="submit" class="btn btn-primary btn-sm" id="save-opinion">Save</button>
						</li>
				</form>
			</div>
		</div>
	</div>
</body>
</html>