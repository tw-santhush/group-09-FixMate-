<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "fixmate_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>