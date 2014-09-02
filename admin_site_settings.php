<?php
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/overall_header.php';
?>
<h1><a href="admin.php">Administration</a> - Site Settings</h1>
<?php
$site_email = get_site_email();
if ( ! empty($_REQUEST)) {
	if (isset($_REQUEST['site-email']) && $site_email != $_REQUEST['site-email']) {
		$_SESSION['admin-save']['site-email'] = 0;
		if (set_site_setting('site_email', $_REQUEST['site-email'])) {
			$_SESSION['admin-save']['site-email'] = 1;
		}
	}
	if (isset($_REQUEST['site-status']) && SITE_STATUS != $_REQUEST['site-status']) {
		$_SESSION['admin-save']['site-status'] = 0;
		if (set_site_setting('site_status', $_REQUEST['site-status'])) {
			$_SESSION['admin-save']['site-status'] = 1;
		}
	}
	header('Location: admin_site_settings.php');
	exit;
}
if (isset($_SESSION['admin-save'])) {
	if (isset($_SESSION['admin-save']['site-email'])) {
		$message = '<h3 style="color: #ff0000;>There was a problem saving the site email!</h3>';
		if ($_SESSION['admin-save']['site-email']) {
			$message = '<h3>Site email saved successfully!</h3>';
		}
		echo $message;
	}
	if (isset($_SESSION['admin-save']['site-status'])) {
		$message = '<h3 style="color: #ff0000;>There was a problem saving the site status!</h3>';
		if ($_SESSION['admin-save']['site-status']) {
			$message = '<h3>Site status saved successfully!</h3>';
		}
		echo $message;
	}
	unset($_SESSION['admin-save']);
}
?>
<div>
	<form method="post" action="admin_site_settings.php">
		<ul>
			<li>
				<label for="site-email">Site Email</label>
				<input type="text" name="site-email" value="<?php echo $site_email;?>">
			</li>
			<li>
				<label for="site-email">Site Status</label>
				<select name="site-status">
					<option value="alpha" <?php echo (SITE_STATUS == ALPHA) ? 'selected="selected"' : '';?>>Alpha</option>
					<option value="beta" <?php echo (SITE_STATUS == BETA) ? 'selected="selected"' : '';?>>Beta</option>
					<option value="release" <?php echo (SITE_STATUS == RELEASE) ? 'selected="selected"' : '';?>>Release</option>
				</select>
			</li>
			<li>
				<button type="submit">Save</button>
			</li>
		</ul>
	</form>
</div>

<?php include 'includes/overall/overall_footer.php';