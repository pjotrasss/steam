<?php
require ("conn.php");
require ("basic_scripts.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['signin']))
    {
        global $conn;
        $username = $_POST['username'];
        $password = $_POST['password'];
        echo $username;
        echo "<br>";
        echo $password;
        $sql = "SELECT PASSWORD FROM passwords JOIN users ON users.ID=passwords.USER_ID WHERE users.EMAIL='$username';";
        echo $sql;
        echo $conn->query($sql);
    };
};