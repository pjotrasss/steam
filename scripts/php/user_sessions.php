<?php
require ('conn.php');

function validate_session() {
    if(isset($_SESSION['session_token'])) {
        $session_token = $_SESSION['session_token'];
        $user_ip = $_SERVER['REMOTE_ADDR'];
        global $conn;

        $sql = "SELECT ID FROM sessions WHERE CURRENT_TIMESTAMP()<EXPIRES_AT AND DELETED_AT IS NULL AND SESSION_TOKEN=? AND IP_ADDRESS=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $session_token, $user_ip);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows>0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
};

function login_profile() {
    session_start();
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "<div>";
        echo    "<p>{$username}<i class='fa fa-caret-down'></i></p>";
        echo    "<div class='subnav'>";
        echo        "<a class='navitem' href='game_library.html.php' class='subnav_item'>My games</a>";
        echo        "<form class='subnav_item' method='post'>";
        echo            "<input type='submit' value='logout' class='subnav_item' name='logout'/>";
        echo        "</form>";
        echo    "</div>";
        echo "</div>";
    } else {
        echo "<a class='navitem' href='login.html'>LOGIN</a>";
    }
};

if(isset($_POST['logout'])) {
    
    session_start();

    if(isset($_SESSION['username'])){
        unset($_SESSION['username']);
    };

    if(isset($_SESSION['session_token'])){
        unset($_SESSION['session_token']);
    };

    if(isset($_SESSION['user_id'])){
        unset($_SESSION['user_id']);
    };

    session_destroy();

    if(isset($_SESSION['session_token'])) {
        global $conn;
        $session_token = $_SESSION['session_token'];
        
        $sql = "UPDATE sessions SET DELETED_AT = CURRENT_TIMESTAMP WHERE SESSION_TOKEN=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $session_token);
        $stmt->execute();
    };
    header('Location: index.html.php');
};