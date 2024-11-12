<?php
global $conn;

$config = parse_ini_file('config.ini', true);
$conn = new mysqli($config['steamdb']['server'],$config['steamdb']['user'],$config['steamdb']['password'], $config['steamdb']['dbname']);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
        die("". $conn->connect_error);
};