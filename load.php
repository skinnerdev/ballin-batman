<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/primary.css">
	</head>
	<body>
		<?php
		include 'includes/blankarray.php'; //currently setting a blank array, will eventually have to load real data
		$alpha = array('a','b','c','d','e','f','g','h','i','j','k', 'l'); //used in identifying opinion location
		?>
		<div id="container">
			<h1>The Factionizer</h1>
				<ul class="menu">
					<li><a href="index.php">Home</a></li>
					<li><a href="new_project.php">New</a></li>
					<li class="selected"><a href="load.php">Open</a></li>
					<li><a href="edit_project.php">Change Numbers</a></li>
					<li><a href="grid.php">Grid</a></li>
					<li><a href="character_card.php">Character Cards</a></li>
				</ul>
			<div id="grid_container">			
			</div>
		</div>
	</body>
</html>
<?php 
include 'includes/overall/overall_footer.php'; ?>