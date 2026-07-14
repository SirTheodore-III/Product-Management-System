<?php
// Change these if your MySQL setup uses different credentials.
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "product_system";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
