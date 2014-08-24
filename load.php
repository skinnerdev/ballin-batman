<?php
error_reporting(0);
include 'core/init.php';
if ( ! empty($_REQUEST['project_id'])) {
	if (setActiveProject($_REQUEST['project_id'])) {
		header("Location: load.php");
	}
}
$projects = getProjectList();
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
			<h1>The Factionizer - Project: <?php echo $activeProject['project_name'];?></h1>
				<ul class="menu">
					<li><a href="index.php">Home</a></li>
					<li><a href="new_project.php">New</a></li>
					<li class="selected"><a href="load.php">Open</a></li>
					<li><a href="edit_project.php">Change Numbers</a></li>
					<li><a href="grid.php">Grid</a></li>
					<li><a href="character_card.php">Character Cards</a></li>
				</ul>
			<div id="grid_container">
				<table class="table">
					<tr>
						<th>Project Name</th>
						<th>&nbsp;</th>
					</tr>
				<?php foreach ($projects as $project) : ?>
					<tr>
						<td><?php echo ($project['project_id'] == $activeProject['project_id']) ? '<strong>' . $project['project_name'] . '</strong>' : $project['project_name'];?></td>
						<td><?php echo ($project['project_id'] == $activeProject['project_id']) ? '<i class="fa fa-check"></i>&nbsp;Active' : '<a href="load.php?project_id=' . $project['project_id'] . '"><i class="fa fa-folder-open-o"></i>&nbsp;Open</a>';?></td>
					</tr>
				<?php endforeach; ?>
				</table>
			</div>
		</div>
	</body>
</html>
<?php 
include 'includes/overall/overall_footer.php'; ?>