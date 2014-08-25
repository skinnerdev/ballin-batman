<?php
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/overall_header.php';
?>
<h1>Administration</h1>
<div>
	<ul>
		<li><a href="site_settings.php">Change Site Settings</a></li>
		<li><a href="admin_users.php">Manage Users</a></li>
		<li><a href="mail.php">Email Users</a></li>
	</ul>
</div>

<?php include 'includes/overall/overall_footer.php';