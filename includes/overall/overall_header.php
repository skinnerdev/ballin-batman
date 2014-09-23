<!doctype html>
<html>
<head>
    <title>The Factionizer</title>
	<meta name="keywords" content="factionizer, larp, the factionizer, double exposure, dexcon, dreamation, create larp, convention, gaming">
	<meta name="description" content="A tool for LARP. Create character factions and see their interactions - then print character cards.">
    <meta charset="UTF-8">	
    <link rel="stylesheet" href="css/screen.css">
	<!--<script type="text/javascript" src="/includes/overall/javascript.js"></script>-->
</head>
<body>
<header>
	<div class="logo">
		<a href="index.php"><img src="/images/Factionizerlogo.png"></a>
	</div>
	<!-- <h1 class="logo">The Factionizer</h1> -->
	<?php
	$home = 'class="selected"';
	$beta = $contact = '';
	if (isset($page)) {
		$home = '';
		${$page} = 'class="selected"';
	}
	?>
	<ul class="menu">
			<li <?php echo $home;?>><a href="index.php">Home</a></li>
			<li <?php echo $contact;?>><a href="contact.php">Contact us</a></li>
			<?php
				include 'includes/check_connect.php';
				if (isset($_SESSION['user_id'])) {
					$projects = get_project_list();
					if (empty($projects)) {
						echo '<li><a href="new_project.php">Create First Project</a></li>';
					} else {
						echo '
						<li><a href="new_project.php">New</a></li>
						<li><a href="load.php">Open</a></li>
						<li><a href="edit_project.php">Edit Active</a></li>
						<li><a href="grid.php">Grid</a></li>
						<li id="character-cards"><a href="character_card.php" target="_blank">Character Cards</a></li>
						<li><a href="print.php" target="_blank">Print Cards</a></li>
						<li><a href="tutorial.php" target="_blank">Tutorial</a></li>';
						
					}
				} else { echo '<li><a href="beta.php">Beta Signup</a></li>';}
			?>
	</ul>
	<div class="clear"></div>
</header>
<div id="container">
	<aside>
		<?php 
		if (is_logged_in() === true) {
			include 'includes/widgets/loggedin.php';
		} else {
			include 'includes/widgets/login.php';
		}
			include 'includes/widgets/user_count.php';
		?>
	</aside>