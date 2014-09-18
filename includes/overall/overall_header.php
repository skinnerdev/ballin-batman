<!doctype html>
<html>
<head>
    <title>The Factionizer</title>
	<meta name="keywords" content="factionizer, larp, the factionizer, double exposure, dexcon, dreamation, create larp, convention, gaming">
	<meta name="description" content="A tool for LARP. Create character factions and see their interactions - then print character cards.">
    <meta charset="UTF-8">	
    <link rel="stylesheet" href="css/screen.css">
	<script src="includes/jquery-1.9.0.min.js"></script>
	<!--<script type="text/javascript" src="/includes/overall/javascript.js"></script>-->
</head>
<body>
<header>
	<div class="logo">
		<img src="/images/Factionizerlogo.png">
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
			<?php if (SITE_STATUS != RELEASE) : ?>
			<li <?php echo $beta;?>><a href="beta.php">Beta Signup</a></li>
			<?php endif; ?>
			<li <?php echo $contact;?>><a href="contact.php">Contact us</a></li>
			<?php
				include 'includes/check_connect.php';
				if (isset($_SESSION['user_id'])) {
					if (
						(SITE_STATUS == ALPHA && has_access($user_id, USER_TYPE_ADMIN)) || // Alpha Testing
						(SITE_STATUS == BETA && user_has_beta()) || // Beta Testing
						(SITE_STATUS == RELEASE) // Release
					) {
						echo '<li><a href="load.php">Open Project</a></li>';
					}
				}
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