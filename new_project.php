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
		echo 'Creating your data...';
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
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
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
			<h2>New Project</h2>
			<p>Create a new project by filling in the fields below.<br> You can adjust the fields later, if needed.</p>
			<form action="" method="post">
				<ul>
					<li>
						<label for="project_name">Project Name*:</label>
						<input type="text" name="project_name"></li>
					<li><label for="faction[1]">Faction 1*:</label>
						<input type="text" name="faction[1]"></li>
					<li><label for="faction[2]">Faction 2*:</label>
						<input type="text" name="faction[2]"></li>
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
					<li>
						<input type="submit" value="Create Project">
					</li>
				</ul>
				Remember, you CAN change these names later!
			</form>
			<?php include 'includes/footer.php'; ?>
		</div>
	</div>
</body>
</html>
