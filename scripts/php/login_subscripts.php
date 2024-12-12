<?php
require ("conn.php");



function select_hashed_password() {
    global $conn;

        $password_sql = "SELECT PASSWORD FROM passwords JOIN users ON users.ID=passwords.USER_ID WHERE users.EMAIL=?;";
        $password_stmt = $conn->prepare($password_sql);
        $password_stmt->bind_param("s", $_POST['username']);
        $password_stmt->execute();
        $password_result = $password_stmt->get_result();
        return mysqli_fetch_array($password_result)['PASSWORD'];
};



function start_user_session() {
    global $conn;

    $session_token = bin2hex(random_bytes(32));
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $session_start_sql = 'INSERT INTO sessions (USER_ID, SESSION_TOKEN, IP_ADDRESS, USER_AGENT) SELECT ID, ?, ?, ? FROM users WHERE users.EMAIL=?;';
    $session_start_stmt = $conn->prepare($session_start_sql);
    $session_start_stmt->bind_param('ssss', $session_token, $user_ip, $user_agent, $_POST['username']);
    
    if($session_start_stmt->execute()) {
        
        $user_id_sql = 'SELECT ID FROM users WHERE EMAIL=?';
        $user_id_stmt = $conn->prepare($user_id_sql);
        $user_id_stmt->bind_param('s',$_POST['username']);
        $user_id_stmt->execute();
        $user_id_result = $user_id_stmt->get_result();
        $user_id = mysqli_fetch_array($user_id_result)['ID'];
        
        session_start();
        $_SESSION['user_data'] = [
            'username' => $_POST['username'],
            'user_id' => $user_id,
            'user_ip' => $user_ip,
            'user_agent' => $user_agent
        ];
        $_SESSION['session_token'] = $session_token;
        
        header('Location: game_library.html.php');
    } else {
        echo 'something went wrong, pleasy try again';
    };
};