<?php

if (!isLoggedIn() || !isAdmin()) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (register($username, $password, $email, $role)) {
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo "Failed to add user.";
    }
}

include 'header.php';

?>

<h1 class="ui dividing header">Add New User</h1>
<div class="ui very padded blue segment">
    <form class="ui form" action="/add/user" method="POST">
        <div class="two fields">
            <div class="field"><label>First Name</label><input type="text" name="first_name" required></div>
            <div class="field"><label>Last Name</label><input type="text" name="first_name" required></div>            
        </div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Password</label><input type="password" name="password" required></div>
        <div class="field">
            <label>Role</label>
            <select name="role" required>
                <option value="User">User</option>
                <option value="Administrator">Administrator</option>
            </select>        
        </div>
        <div class="field"><button class="ui button" type="submit">Add User</button></div>
    </form><br><hr>
</div>