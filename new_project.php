<?php
include 'core/init.php';
protect_page();
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
<?php
if (empty($_POST) === false) {
	$required_fields = array('project_name', 'faction_1', 'faction_2');  // can add other fields here later
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields marked with an asterisk are required.';
			break 1;
		}
	}
}

?>
<div id="container">
	<h1>The Factionizer</h1>
	<ul class="menu">
		<li><a href="index.php">Home</a></li>
		<li class="selected"><a href="new_project.php">New</a></li>
		<li><a href="load.php">Open</a></li>
		<li><a href="edit_project.php">Change Numbers</a></li>
		<li><a href="grid.php">Grid</a></li>
		<li><a href="character_card.php">Character Cards</a></li>
	</ul>
	<div id="grid_container">	

		<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=edit_project.php?new">';
	exit();
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		echo 'Creating your data...';
		
		$user_id = $_SESSION['user_id'];
		$project_name = $_POST['project_name'];
		
		$project_data = array(); 
		$post_var = 1; 
		$faction_qty = 1; 
		while ($post_var<=12) { //sets the array to contain only posted info
			if (empty($_POST['faction_' . $faction_qty]) == false) {
				$project_data['faction_' . $post_var] = $_POST['faction_' . $faction_qty];
				$faction_qty++;
			}
			$post_var++;
		}
		
	
		create_project($project_data, $project_name, $user_id, $faction_qty); //creates the project
		$project_id = mysql_result(mysql_query("SELECT `project_id` FROM `projects` WHERE `project_name`='$project_name'"), 0);
		create_rand_data($project_id);
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=new_project.php?success">';
		exit();
	} else if (empty($errors) === false) {
		echo output_errors($errors);
	}
		?>
		<p><h2>Create a new project by filling in the fields below.<br>
		You can adjust the fields later, if needed.</h2></p>
		<form action="" method="post">
			<ul>
				<li>Project Name*:&nbsp;
					<input type="text" name="project_name"></li>
				<li>Faction 1*: &nbsp; 
					<input type="text" name="faction_1"></li>
				<li>Faction 2*:&nbsp; 
					<input type="text" name="faction_2"></li>
				<li>Faction 3 (leave blank if none):&nbsp; 
					<input type="text" name="faction_3"></li>
				<li>Faction 4 (leave blank if none):&nbsp; 
					<input type="text" name="faction_4"></li>
				<li>Faction 5 (leave blank if none):&nbsp; 
					<input type="text" name="faction_5"></li>
				<li>Faction 6 (leave blank if none):&nbsp; 
					<input type="text" name="faction_6"></li>
				<li>Faction 7 (leave blank if none):&nbsp; 
					<input type="text" name="faction_7"></li>
				<li>Faction 8 (leave blank if none):&nbsp; 
					<input type="text" name="faction_8"></li>
				<li>Faction 9 (leave blank if none):&nbsp; 
					<input type="text" name="faction_9"></li>
				<li>Faction 10 (leave blank if none):
					<input type="text" name="faction_10"></li>
				<li>Faction 11 (leave blank if none):
					<input type="text" name="faction_11"></li>
				<li>Faction 12 (leave blank if none):
					<input type="text" name="faction_12"></li>
				<li>
					<br><input type="submit" value="Create Project"> &nbsp; &nbsp; Remember, you CAN change these names later!</li>
			</ul>
		</form>
<?php
}
 include 'includes/footer.php';
 ?>
</div>
</body>
</html>
