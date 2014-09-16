<?php
include 'core/init.php';
protect_page();
if ( ! empty($_POST)) {
	$required_fields = array('project_name', 'faction_1', 'faction_2');  // can add other fields here later
	foreach($_POST as $key => $value) {
		if (empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields marked with an asterisk are required.';
			break;
		}
	}
	if ( ! empty($errors)) {
		echo output_errors($errors);
	} else {
		$user_id = $_SESSION['user_id'];
		$project_name = $_POST['project_name'];
		$project_data = array();
		$i = 1;
		foreach ($_POST['faction'] as $faction) {
			if ( ! empty($faction)) {
				$project_data['faction'][$i++] = $faction;
			}
		}
		$faction_qty = count($project_data['faction']);
		// Check to make sure there isn't already a project named this
		$project_id = create_project($project_data, $project_name, $user_id, $faction_qty); //creates the project
		create_rand_data($project_id);
		$_SESSION['new-project'] = true;
		header("Location: edit_project.php");
		exit;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/primary.css">
	<link rel="stylesheet"  href="css/bootstrap-tour.min.css">
	<script src="includes/jquery-1.9.0.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="javascript/bootstrap-tour.min.js"></script>
	<script type="text/javascript">
	var user_viewed_tutorial = <?php echo $user_data['viewed_tutorial'];?>;
	var user_id = <?php echo $_SESSION['user_id'];?>;
	</script>
	<script src="javascript/tour.js"></script>
</head>
<body>
	<div id="container">
		<!-- <h1>The Factionizer</h1> -->
		<div class="logo">
			<img src="/images/Factionizerlogo.png" alt="The Factionizer">
		</div>
		<ul class="menu">
			<li><a href="index.php">Home</a></li>
			<li class="selected"><a href="new_project.php">New</a></li>
			<li><a href="load.php">Open</a></li>
			<li><a href="edit_project.php">Edit Project</a></li>
			<li><a href="grid.php">Grid</a></li>
			<li><a href="character_card.php">Character Cards</a></li>
			<li><a href="print.php" target="_blank">Print CC's</a></li>
		</ul>
		<div id="grid_container">
			<button id="tour-start" data-tour class="btn btn-primary" style="float:right;"><i class="fa fa-question-circle"></i>&nbsp;Show the Tour</button>
			<h2>New Project</h2>
			<p>Create a new project by filling in the fields below.<br> You can adjust the fields later, if needed.</p>
			<form action="" method="post">
				<ul>
					<li>
						<label for="project_name">Project Name*:</label>
						<input type="text" name="project_name" id="project-name">
					</li>
					<div id="faction-list" style="max-width: 460px; padding: 15px;">
					<li><label for="faction[1]">Faction 1*:</label>
						<input type="text" name="faction[1]" id="faction-one"></li>
					<li><label for="faction[2]">Faction 2*:</label>
						<input type="text" name="faction[2]" id="faction-two"></li>
					<li><label for="faction[3]">Faction 3 (leave blank if none):</label>
						<input type="text" name="faction[3]"></li>
					<li><label for="faction[4]">Faction 4 (leave blank if none):</label>
						<input type="text" name="faction[4]"></li>
					<li><label for="faction[5]">Faction 5 (leave blank if none):</label>
						<input type="text" name="faction[5]"></li>
					<li><label for="faction[6]">Faction 6 (leave blank if none):</label>
						<input type="text" name="faction[6]"></li>
					<li><label for="faction[7]">Faction 7 (leave blank if none):</label>
						<input type="text" name="faction[7]"></li>
					<li><label for="faction[8]">Faction 8 (leave blank if none):</label>
						<input type="text" name="faction[8]"></li>
					<li><label for="faction[9]">Faction 9 (leave blank if none):</label>
						<input type="text" name="faction[9]"></li>
					<li><label for="faction[10]">Faction 10 (leave blank if none):</label>
						<input type="text" name="faction[10]"></li>
					<li><label for="faction[11]">Faction 11 (leave blank if none):</label>
						<input type="text" name="faction[11]"></li>
					<li><label for="faction[12]">Faction 12 (leave blank if none):</label>
						<input type="text" name="faction[12]"></li>
					</div>
					<li>
						<input type="submit" value="Create Project" id="create-project">
					</li>
				</ul>
				Remember, you CAN change these names later!
			</form>
			<?php include 'includes/footer.php'; ?>
		</div>
	</div>
</body>
</html>
