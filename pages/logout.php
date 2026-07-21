<?php
include '../includes/config.php';

// Destroy session
session_destroy();

// Redirect to home
header('Location: home.php');
exit();
?>