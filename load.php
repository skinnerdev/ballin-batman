<?php
error_reporting(0);
include 'core/init.php';
if ( ! empty($_REQUEST['project_id'])) {
	if (set_active_project($_REQUEST['project_id'])) {
		header("Location: load.php");
	}
}
$projects = get_project_list();
if (empty($projects)) {  //redirects if there's no active project for the user (if they've not created one)
	header("Location: new_project.php");
	exit;
}
if ( ! empty($_GET) && isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
	$deleteProject = get_project($_GET['id']);
	if (empty($deleteProject)) {
		$_SESSION['delete-project-message'] = "No project with that ID exists.";
		header("Location: load.php");
		exit;
	}
	if ($deleteProject['user_id'] != $_SESSION['user_id']) {
		$_SESSION['delete-project-message'] = "You do not have access to that project.";
		header("Location: load.php");
		exit;
	}
	if ($deleteProject['project_id'] == $activeProject['project_id']) {
		$_SESSION['delete-project-message'] = "Unable to delete active project. Switch to a different project to delete this one.";
		header("Location: load.php");
		exit;		
	}
	delete_project($deleteProject['project_id']);
	$_SESSION['delete-project-message'] = "Project deleted successfully.";
	header("Location: load.php");
	exit;
}

$message = null;
if (isset($_SESSION['delete-project-message'])) {
	$message = $_SESSION['delete-project-message'];
	unset($_SESSION['delete-project-message']);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/primary.css">
		<script src="includes/jquery-1.9.0.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		<style>
		i.fa-times {
			color: #ff0000;
		}
		.delete_project {
			cursor: pointer;
		}
		</style>
	</head>
	<body>
		<div id="container">
			<!-- <h1>The Factionizer - Project: <?php echo $activeProject['project_name'];?></h1> -->
			<div class="logo">
				<img src="/images/Factionizerlogo.png">
			</div>
			<ul class="menu">
				<li><a href="index.php">Home</a></li>
				<li><a href="new_project.php">New</a></li>
				<li class="selected"><a href="load.php">Open</a></li>
				<li><a href="edit_project.php">Edit Project</a></li>
				<li><a href="grid.php">Grid</a></li>
				<li><a href="character_card.php">Character Cards</a></li>
				<li><a href="print.php" target="_blank">Print CC's</a></li>
			</ul>
			<div id="grid_container">
				<?php if ( ! empty($message)) {
					echo '<h4 style="color: #548B54">' . $message . '</h4>';
				}?>
				<table class="table">
					<tr>
						<th>Project Name</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				<?php foreach ($projects as $project) : ?>
					<tr>
						<td><?php echo ($project['project_id'] == $activeProject['project_id']) ? '<strong>' . $project['project_name'] . '</strong>' : $project['project_name'];?></td>
						<td>
							<?php echo ($project['project_id'] == $activeProject['project_id']) ? '<i class="fa fa-check"></i>&nbsp;Active' : '<a href="load.php?project_id=' . $project['project_id'] . '"><i class="fa fa-folder-open-o"></i>&nbsp;Open</a>';?>
						</td>
						<td>
							<?php if ($project['project_id'] == $activeProject['project_id']) : ?>
							&nbsp;
							<?php else: ?>
							<a title="Delete Project" class="delete_project" id="delete_project_<?php echo $project['project_id']; ?>" data-project-id="<?php echo $project['project_id'];?>" data-project-name="<?php echo $project['project_name'];?>"><i class="fa fa-times fa-1x"></i>&nbsp;Delete</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</table>
			<?php include 'includes/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
jQuery(document).ready(function() {
	$('.delete_project').click(function(e) {
		e.preventDefault();
		var project_num = $(this).data('project-id');
		var project_name = $(this).data('project-name');
		if (confirm('Are you sure you want to delete Project: ' + project_name + '? This will remove all factions and characters as well. This action cannot be undone.')) {
			window.location.replace("load.php?action=delete&id=" + project_num);
		}
	});
});
</script>