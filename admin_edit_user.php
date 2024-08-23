<?php

if (!isLoggedIn() || !isAdmin()) {
    header('Location: /login');
    exit;
}

if (isset($uri[4])) {
    $id = $uri[4];
    $user = getUserById($id);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        if (updateUser($id, $first_name, $last_name, $username, $email, $role)) {
            header('Location: /admin/users');
            exit;
        } else {
            echo '<div class="ui error message">Failed to update user.</div>';
        }

    }
} else {
    header('Location: /admin/users');
    exit;
}
?>

<h1 class="ui dividing header">Edit User</h1>
<div class="ui very padded blue segment">
<form class="ui form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
    <div class="two fields">
        <div class="field"><label>First Name</label><input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required></div>
        <div class="field"><label>Last Name</label><input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required></div>        
    </div>
    <div class="field"><label>Username</label><input type="text" name="username" value="<?php echo $user['username']; ?>" required></div>
    <div class="field"><label>Email</label><input type="email" name="email" value="<?php echo $user['email']; ?>" required></div>
    <div class="field">
        <label>Role</label>
        <select name="role" required>
            <option value="User" <?php if ($user['role'] == 'User') echo 'selected'; ?>>User</option>
            <option value="Administrator" <?php if ($user['role'] == 'Administrator') echo 'selected'; ?>>Administrator</option>
        </select>
    </div>
    <div class="field"><button class="ui button" type="submit">Update User</button></div>    
</form>
</div>