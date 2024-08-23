<?php
include 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (deleteUser($id)) {
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo "Failed to delete user.";
    }
} else {
    header('Location: admin_dashboard.php');
    exit;
}
?>