<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_reschedule_system_2022e164";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
