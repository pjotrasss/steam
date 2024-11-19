<?php
require ('conn.php');

if(isset($_COOKIE['user_session'])){
    echo "working";
    $session_token = $_COOKIE['user_session'];
    // global $conn;

    // $sql = "SELECT SESSION_TOKEN, IP_ADDRESS FROM users_sessions WHERE CURRENT_TIMESTAMP()<EXPIRES_AT AND SESSION_TOKEN=?";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("s", $session_token);
    // $stmt->execute();
} else {
    header(location: '../../login.html');
};