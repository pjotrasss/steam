<?php
require ("conn.php");
require ("basic_scripts.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['signin']))
    {
        global $conn;
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT PASSWORD FROM passwords JOIN users ON users.ID=passwords.USER_ID WHERE users.EMAIL=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows>0) {
            $hashed_password =  mysqli_fetch_array($result)['PASSWORD'];
            if (password_verify($password,$hashed_password)) {
                echo "logged";
            } else {
                echo "Incorrect username or password";
            };
        } else {
            echo "Incorrect username or password";
        };
        $conn->close();
    };
};