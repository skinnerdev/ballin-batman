<?php
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/overall_header.php';

?>

<h1>Email All Users</h1><br>
<p></p>

<?php
if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
	?>
		<h2><p>Your email has been sent!</p></h2>
	<?php
} else {
	if (empty($_POST) === false) {
		if (empty($_POST['subject']) === true) {
			$errors[] = 'Please enter a subject.';
		}
		if (empty($_POST['body']) === true) {
			$errors[] = 'Please enter a message.';
		}
		
		if (empty($errors) === false) {
			echo output_errors($errors);			
		} else {
			mail_users($_POST['subject'], $_POST['body']);
			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=mail.php?success">';
			exit();
		}
	}

	?>
	<p>Note: Personal greeting and signature is ALREADY inserted!</p>
	<form action="" method="post">
	<ul>
		<li>
			Subject*:<br>
			<input type="text" name="subject">
		</li>
		<li>
			Body*:<br>
			<textarea name="body"></textarea>
		</li>
		<li>
			<input type="submit" value="Sent Email">
		</li>

	</ul>
	</form>



	<?php
}
include 'includes/overall/overall_footer.php'; ?>