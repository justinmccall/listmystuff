<?php

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

$users = getAllUsers();

include 'header.php';

?>

<div class="ui two column grid">
    <div class="column"><h2>User Management</h2></div>
    <div class="right aligned column"><a href="/admin/users/add" class="ui primary labeled icon blue button"><i class="ui plus icon"></i>Add New User</a></div>
</div>
<table class="ui table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['first_name']." ".$user['last_name']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
                <a href="/admin/edit/user/<?php echo $user['id']; ?>">Edit</a> | 
                <a href="/admin/delete/user/<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>