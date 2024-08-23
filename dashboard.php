<?php

if (!isLoggedIn()) {
    header('Location: /login');
    exit;
}

include 'header.php';

echo '<h1 class="ui dividing header">Welcome, '.$_SESSION['first_name'].'</h1>';

?>

