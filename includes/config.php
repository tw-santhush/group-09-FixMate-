<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "fixmate_db";

$config_local = __DIR__ . '/config.local.php';
if (file_exists($config_local)) {
    include $config_local;
}

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>