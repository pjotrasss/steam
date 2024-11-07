<?php
require ("conn.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['register']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $rpassword = $_POST['rpassword'];
        if ($rpassword === $password) {
            global $conn;
            echo 'passwords match<br>';
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (EMAIL) VALUES ('$username');";
            $conn->query($sql);
            $sql2 = "INSERT INTO passwords (USER_ID, PASSWORD) SELECT ID, '$hashed_password' FROM users WHERE EMAIL='$username';";
            $conn->query($sql2);
        } else {
            echo "ERROR - Can't register - passwords are not the same";
            return false;
        };
    };
};