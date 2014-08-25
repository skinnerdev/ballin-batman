<?php
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/overall_header.php';
$users = get_user_list();
?>
<link rel="stylesheet" type="text/css" href="/javascript/datatables/media/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="/javascript/datatables/media/js/jquery.dataTables.js"></script>

<h1><a href="admin.php">Administration</a> - Manage Users</h1>
<div>
	<ul>
		<li>Search all users</li>
		<li>To edit a user, click on the username</li>
	</ul>
	<table id="users-table" class="hover stripe" style="cursor: pointer;">
		<thead>
			<tr>
				<th>Username</th>
				<th>Full Name</th>
				<th>Email</th>
				<th>Active</th>
				<!-- <th>Type</th> -->
				<!-- <th>Can Email?</th> -->
				<th>Beta?</th>
				<!-- <th>Active Project</th> -->
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user) : ?>
			<tr>
				<td><a href="admin_edit_user.php?user_id=<?php echo $user['user_id'];?>"><?php echo $user['username'];?></a></td>
				<td><?php echo $user['first_name'] . ' ' . $user['last_name'];?></td>
				<td><?php echo $user['email'];?></td>
				<td><?php echo ($user['active']) ? 'Yes' : 'No';?></td>
				<!-- <td><?php echo ($user['type']) ? 'Admin' : 'Normal';?></td> -->
				<!-- <td><?php echo ($user['allow_email']) ? 'Yes' : 'No';?></td> -->
				<td><?php echo ($user['beta']) ? 'Yes' : 'No';?></td>
				<!-- <td><?php echo $user['active_project'];?></td> -->
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(document).ready( function () {
    $('#users-table').DataTable({
    	"order": [[ 0, "asc" ]]
    });
} );
</script>
<?php include 'includes/overall/overall_footer.php';