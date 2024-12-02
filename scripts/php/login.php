<?php
require ("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['signin'])) {
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
                echo "You've been succesfully logged in, redirecting to home page...";

                $cookie_name = "user_session";
                $session_token = bin2hex(random_bytes(32));
                $user_ip = $_SERVER['REMOTE_ADDR'];

                $sql2 = 'INSERT INTO sessions (USER_ID, SESSION_TOKEN, IP_ADDRESS) SELECT ID, ?, ? FROM users WHERE users.EMAIL=?;';
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param('sss', $session_token, $user_ip, $username);
                
                if($stmt2->execute()) {
                    setcookie($cookie_name, $session_token, [
                        'path' => '/',
                        'domain' => '',
                        'secure' => false,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                    session_start();
                    $_SESSION['username'] = $username;
                    $_SESSION['session_token'] = $session_token;
                    header('Location: index.html.php');
                } else {
                    echo 'something went wrong, pleasy try again';
                };

            } else {
                echo "Incorrect username or password";
            };
        } else {
            echo "Incorrect username or password";
        };
        $conn->close();
    };
};