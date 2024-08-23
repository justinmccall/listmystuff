<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (sendPasswordReset($email)) {
        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with that email.";
    }
}
?>

<form action="forgot_password.php" method="POST">
    Enter your email: <input type="email" name="email" required><br>
    <button type="submit">Send Reset Link</button>
</form>