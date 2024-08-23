<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    if (resetPassword($token, $new_password)) {
        echo "Password has been reset successfully. You can now <a href='login.php'>login</a>.";
    } else {
        echo "Failed to reset password. The token might be invalid or expired.";
    }
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("No token provided.");
}
?>

<form action="reset_password.php" method="POST">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    New Password: <input type="password" name="new_password" required><br>
    <button type="submit">Reset Password</button>
</form>