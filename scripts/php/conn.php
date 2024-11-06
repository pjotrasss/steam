<?php
global $conn;
$server = "localhost";
$user = "root";
$password = "";
$dbname = "steam";
$conn = new mysqli($server, $user, $password, $dbname);
if ($conn->connect_error) {
        die("". $conn->connect_error);
};