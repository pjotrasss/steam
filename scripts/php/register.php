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
                $sql = "INSERT INTO users (EMAIL) VALUES (?);";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
            } catch (mysqli_sql_exception $error) {
                //header("Location: ")
                if ($error->getCode() === 1062) {
                    echo "Error: username is already taken";
                } else {
                    echo "Unpredicted error occured: {$error->getMessage()}";
                };
                exit;
            };

            $sql2 = "INSERT INTO passwords (USER_ID, PASSWORD) SELECT ID, ? FROM users WHERE EMAIL=?;";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ss", $hashed_password, $username);
            $stmt2->execute();

        } else {
            echo "ERROR - Can't register - passwords are not the same";
            return false;
        };
        $conn->close();
    };
};