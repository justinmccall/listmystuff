<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (register($username, $password, $email)) {
        echo "Registration successful! You can now <a href='login.php'>login</a>.";
    } else {
        echo "Registration failed. Username or email might be taken.";
    }
}
?>

<form action="register.php" method="POST">
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>