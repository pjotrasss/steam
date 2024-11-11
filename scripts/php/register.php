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
            echo 'passwords match';
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (EMAIL) VALUES (?);";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();

            $sql2 = "INSERT INTO passwords (USER_ID, PASSWORD) SELECT ID, ? FROM users WHERE EMAIL=?;";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ss", $hashed_password, $username);
            $stmt2->execute();
        } else {
            echo "ERROR - Can't register - passwords are not the same";
            return false;
        };
    };
};