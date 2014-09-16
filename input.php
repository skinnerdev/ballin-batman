<?php
include 'core/init.php';
protect_page();

if (empty($_GET['bearer']) || empty($_GET['receiver'])) {
	echo "Invalid input.";
	exit();
}

$user_id = $_SESSION['user_id'];
$bearer_id = $_GET['bearer'];
$receiver_id = $_GET['receiver'];
$project_id = $activeProject['project_id'];
$type = null;
if ( ! empty($_GET['type'])) {
	$type = $_GET['type'];
}
if (empty($type)) {
	echo "No type set";
	exit();
}

$opinion = get_character_opinions($type, $project_id, $bearer_id, $receiver_id);
$data_from = $opinion['from'];

$note_text = '';
$subHeader = '';
$header = '';
switch ($type) {
	case 'c2c':
		if ( ! empty($opinion['c2c']['opinion_word'])) {
			$note_text .= "Opinion of character {$opinion['receiver']['character']['character_name']}: <strong>" . $opinion['c2c']['opinion_word'] . "</strong><br >";
		} else {
			$note_text .= "No specific opinion of character {$opinion['receiver']['character']['character_name']}<br >";
		}
	case 'c2f':
		if ( ! empty($opinion['c2f']['opinion_word'])) {
			$note_text .= "Opinion of faction {$opinion['receiver']['faction']['faction_name']}: <strong>" . $opinion['c2f']['opinion_word'] . "</strong><br >";
		} else {
			$note_text .= "No specific opinion of faction {$opinion['receiver']['faction']['faction_name']}<br >";
		}
	case 'f2f':
		if ( ! empty($opinion['f2f']['opinion_word'])) {
			$note_text .= "Faction {$opinion['bearer']['faction']['faction_name']} opinion of faction {$opinion['receiver']['faction']['faction_name']}: <strong>" . $opinion['f2f']['opinion_word'] . "</stong><br >";
		} else {
			$note_text .= "Faction {$opinion['bearer']['faction']['faction_name']} has no specific opinion of faction {$opinion['receiver']['faction']['faction_name']}<br >";
		}
		break;
	default:
		$note_text = "No opinion set.";
}

$noOpinionText = "No Opinion";
switch ($type) {
	case 'c2c':
		// $header = "Character's opinion of Character";
		// $subHeader = "{$opinion['bearer']['character']['character_name']} ({$opinion['bearer']['faction']['faction_name']}) to {$opinion['receiver']['character']['character_name']} ({$opinion['receiver']['faction']['faction_name']}): {$opinion['opinion_word']}";
		$header = "{$opinion['bearer']['character']['character_name']}'s opinion of {$opinion['receiver']['character']['character_name']}: {$opinion['opinion_word']}";
		$noOpinionText = "Use Faction Opinion";
		break;
	case 'c2f':
		// $header = "Character's opinion of Faction";
		// $subHeader = "{$opinion['bearer']['character']['character_name']} ({$opinion['bearer']['faction']['faction_name']}) to {$opinion['receiver']['faction']['faction_name']}: {$opinion['opinion_word']}";
		$header = "{$opinion['bearer']['character']['character_name']}'s opinion of {$opinion['receiver']['faction']['faction_name']}: {$opinion['opinion_word']}";
		if ( ! empty($opinion['f2f'])) {
			$noOpinionText = "Use Faction Opinion";
		}
		$noOpinionText = "Use Faction Opinion";
		break;
	case 'f2f':
		// $header = "Faction's opinion of Faction";
		// $subHeader = "{$opinion['bearer']['faction']['faction_name']} to {$opinion['receiver']['faction']['faction_name']}: {$opinion['opinion_word']}";
		$header = "{$opinion['bearer']['faction']['faction_name']}'s opinion of {$opinion['receiver']['faction']['faction_name']}: {$opinion['opinion_word']}";
		break;
}

if ( ! empty($_POST['opinion_word']) && isset($_POST['opinion_text'])) {
	$opinion_word = $_POST['opinion_word'];
	$opinion_text = $_POST['opinion_text'];
	$opinion_id = false;
	if ( ! empty($opinion[$type]['opinion_id'])) {
		$opinion_id = $opinion[$type]['opinion_id'];
	}
	$results = save_opinion($type, $project_id, $bearer_id, $receiver_id, $opinion_word, $opinion_text, $opinion_id);
	$saveMessage = "There was a problem saving.";
	if ($results) {
		$saveMessage = "Save successful!";
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
</head>
<body>
	<div class="container-fluid" id="inputcontainer">
		<?php if ( ! empty($saveMessage)) {
			echo "<h2>$saveMessage</h2>";
			echo "<script>parent.jQuery.colorbox.close(); parent.location.reload();</script></div></body></html>";
			exit;
		}?>
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo $header;?></h2>
				<h4><?php echo $subHeader;?></h4>
				<?php echo $note_text;?><br>
			</div>
		</div>
		<div class="row">
			<form autocomplete="off" enctype="multipart/form-data" action="input.php?type=<?php echo $type;?>&bearer=<?php echo $bearer_id;?>&receiver=<?php echo $receiver_id;?>" novalidate="" name="input" method="POST">
				<div class="col-md-6">
				<?php foreach ($opinionWords as $key => $value) :
					$checked = ( ! empty($opinion[$type]['opinion_word']) && $key == $opinion[$type]['opinion_word']) ? 'checked="checked"' : '';
				?>
					<input <?php echo $checked;?> type="radio" name="opinion_word" value="<?php echo $key;?>" class="opinion">&nbsp;<?php echo $value;?><br>
				<?php $checked = (empty($opinion[$type]['opinion_word'])) ? 'checked="checked"' : ''; ?>
				<?php endforeach; ?>
				<input <?php echo $checked;?> type="radio" name="opinion_word" value="no_opinion" class="opinion">&nbsp;<?php echo $noOpinionText; ?><br>
				<br />
				<button type="submit" class="btn btn-primary btn-lg" id="save-opinion">Save</button>
				</div>
				<div class="col-md-6">
					<textarea <?php echo ($checked) ? 'disabled' : '';?> placeholder="Additional Notes" name="opinion_text" rows="10" cols="50" id="opinion-text" data-opinion-text="<?php echo ( ! empty($opinion[$type]['opinion_text'])) ? $opinion[$type]['opinion_text'] : '';?>"><?php echo ( ! empty($opinion[$type]['opinion_text'])) ? $opinion[$type]['opinion_text'] : '';?></textarea>	
				</div>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript">
jQuery(document).ready(function() {
	$('.opinion').click(function() {
		if (this.value == 'no_opinion') {
			$("#opinion-text").html('');
			$("#opinion-text").prop('disabled', true);
		} else {
			$("#opinion-text").html($("#opinion-text").data('opinion-text'));
			$("#opinion-text").prop('disabled', false);
		}
	});
});
</script>
</html>