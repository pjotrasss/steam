<?php
require ("conn.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $rpassword = $_POST['rpassword'];
        if ($rpassword === $password) {
            
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            global $conn;

            try {
                $email_insert_sql = "INSERT INTO users (EMAIL) VALUES (?);";
                $email_insert_stmt = $conn->prepare($email_insert_sql);
                $email_insert_stmt->bind_param("s", $username);
                $email_insert_stmt->execute();
                $inserted_id = $conn->insert_id;
            } catch (mysqli_sql_exception $error) {
                if ($error->getCode() === 1062) {
                    echo "Error: username is already taken";
                } else {
                    echo "Unpredicted error occured: {$error->getMessage()}";
                };
                exit;
            };

            try {
                $password_insert_sql = "INSERT INTO passwords (USER_ID, PASSWORD) VALUES (?,?);";
                $password_insert_stmt = $conn->prepare($password_insert_sql);
                $password_insert_stmt->bind_param("is", $inserted_id, $hashed_password);
                $password_insert_stmt->execute();
            } catch (mysqli_sql_exception $error) {
                echo "Something went wrong, please try again";
            };
        } else {
            echo "ERROR - Can't register - passwords are not the same";
        };
    };
};