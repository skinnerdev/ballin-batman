<?php
include 'core/init.php';
$page = 'beta';
include 'includes/overall/overall_header.php';
$status = (SITE_STATUS == ALPHA) ? 'Alpha' : 'Beta';
?>

<h1>Beta Test Signup</h1>
<?php if ( ! is_logged_in()) : ?>
	Please register or log in to sign up for the beta!
<?php elseif (user_has_beta()) : ?>
	You've already signed up for the beta!
	<p>In the meantime, here's a screenshot to keep you interested!</p>
	<img src="images/screenshot.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;"><br>
	<img src="images/screenshot2.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;">
<?php else:
	if (isset($_GET['success']) && empty($_GET['success']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != false &&  ! empty($name)) {
		add_user_to_beta($_POST['comments']);
		?>
		<p><h2>Your application was submitted!</h2></p>
		<p>We will contact you when the Beta is live.<p>
		<p>In the meantime, here's a screenshot to keep you interested!</p>
		<img src="images/screenshot.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;">
		<img src="images/screenshot2.png" alt="Factionizer Screenshot" style="max-height: 500px; max-width: 600px;">
		<?php
	} else {
		if (empty($errors) === false) {
			echo output_errors($errors);
		}
		?>
		<p><h2>The Factionizer is currently in <?php echo $status;?> testing.  If you would like to be a Beta tester, please fill out the form below:</p></h2>
		<form action="beta.php?success" method="POST">
		<ul>
			<li><textarea rows="20" cols="20" name="comments" onClick="this.value=''">Comments</textarea></li>
			<li><input type="submit" name="submit" value="Submit"/></li>
		</ul>
		<?php
	}
endif;
include 'includes/overall/overall_footer.php'; ?>