<?php
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/overall_header.php';
if (empty($_GET['user_id'])) {
	header("Location: admin_users.php");
	exit;
}
$edit_user_id = $_GET['user_id'];
$edit_user = get_user_data($edit_user_id);
$fullname = $edit_user['first_name'] . ' ' . $edit_user['last_name'];
?>
<h1><a href="admin.php">Administration</a> - <a href="admin_users.php">Manage Users</a> - Edit User (<?php echo $fullname;?>)</h1>
<?php
if ( ! empty($_POST) && isset($_POST['user_id'])) {
	pr($_POST);
	$new_data = $_POST;
	$save_data = array();
	foreach ($new_data as $key => $value) {
		if (isset($edit_user[$key]) && $edit_user[$key] != $value) {
			$save_data[$key] = $value;
		}
	}
	if ( ! empty($save_data)) {
		$_SESSION['admin-user-save'] = 0;
		if (update_user($_POST['user_id'], $save_data)) {
			$_SESSION['admin-user-save'] = 1;
		}
	}
	header('Location: admin_edit_user.php?user_id=' . $_POST['user_id']);
	exit;
}
if (isset($_SESSION['admin-user-save'])) {
	$message = '<h3 style="color: #ff0000;>There was a problem saving the user data!</h3>';
	if ($_SESSION['admin-user-save']) {
		$message = '<h3>User data saved successfully!</h3>';
	}
	echo $message;
	unset($_SESSION['admin-user-save']);
}
?>
<link rel="stylesheet" type="text/css" href="/javascript/datatables/media/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="/javascript/datatables/media/js/jquery.dataTables.js"></script>
<div>
	<?php if ( ! empty($edit_user['profile'])) : ?>
	<img src="<?php echo $edit_user['profile'];?>">
	<?php endif; ?>
	<form method="post" action="admin_edit_user.php?user_id=<?php echo $edit_user_id;?>">
		<ul>
			<li>
				<label for="disabled_user_id">User ID</label><br>
				<input type="text" name="disabled_user_id" value="<?php echo $edit_user['user_id'];?>" disabled="disabled"></input>
				<input type="hidden" name="user_id" value="<?php echo $edit_user['user_id'];?>"></input>
			</li>
			<li>
				<label for="user_name">Username</label><br>
				<input type="text" name="username" value="<?php echo $edit_user['username'];?>"></input>
			</li>
			<li>
				<label for="first_name">First Name</label><br>
				<input type="text" name="first_name" value="<?php echo $edit_user['first_name'];?>"></input>
			</li>
			<li>
				<label for="last_name">Last Name</label><br>
				<input type="text" name="last_name" value="<?php echo $edit_user['last_name'];?>"></input>
			</li>
			<li>
				<label for="email">Email</label><br>
				<input type="text" name="email" value="<?php echo $edit_user['email'];?>"></input>
			</li>
			<li>
				<label for="active">Active</label><br>
				<input type="text" name="active" value="<?php echo $edit_user['active'];?>"></input>
			</li>
			<li>
				<label for="password_recover">Password Recover</label><br>
				<input type="text" name="password_recover" value="<?php echo $edit_user['password_recover'];?>"></input>
			</li>
			<li>
				<label for="type">Type</label><br>
				<input type="text" name="type" value="<?php echo $edit_user['type'];?>"></input>
			</li>
			<li>
				<label for="allow_email">Allow Email</label><br>
				<input type="text" name="allow_email" value="<?php echo $edit_user['allow_email'];?>"></input>
			</li>
			<li>
				<label for="Profile">Profile Photo</label><br>
				<input type="text" name="profile" value="<?php echo $edit_user['profile'];?>"></input>
			</li>
			<li>
				<label for="beta">Beta</label><br>
				<input type="text" name="beta" value="<?php echo $edit_user['beta'];?>"></input>
			</li>
			<li>
				<label for="active_project">Active Project</label><br>
				<input type="text" name="active_project" value="<?php echo $edit_user['active_project'];?>"></input>
			</li>
			<li>
				<button type="submit">Save</button>
			</li>
		</ul>
	</form>
</div>
<?php include 'includes/overall/overall_footer.php';