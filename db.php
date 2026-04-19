<?php
$conn = new mysqli("localhost", "root", "", "weather_app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>