<?php
// include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    if (login($email, $password, $remember_me)) {
        if(!isAdmin()){
            header('Location: dashboard.php');
        }else{
            header('Location: /dashboard');
        }
        exit;

    } else {
        echo "Login failed. Invalid username or password.";
    }
}

include 'header.php';
?>
<div class="ui middle aligned center aligned grid">
    <div class="column">
        <form class="ui form" action="/login" method="POST" style="width: 350px; margin: 0 auto;">
        <div class="field"><input type="text" name="email" required></div>
        <div class="field"><input type="password" name="password" required></div>
        <div class="field"><input type="checkbox" name="remember_me"> Remember Me<br></div>
        <div class="field"><button class="ui button" type="submit">Login</button></div>        
        </form>
        <a href="forgot_password.php">Forgot your password?</a>
    </div>
</div>

<?php

$retrieved_hashed_password = '$2y$10$E1NKZxslZC1VrS2Y/ONzGeYvXlSIXqV17W0iT7Os6h4QIFuUAvukG';
if (password_verify('password123', $retrieved_hashed_password)) {
    echo "Password is correct!";
} else {
    echo "Password is incorrect!";
}