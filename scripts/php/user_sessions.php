<?php
require ('conn.php');
session_start();

function soft_session_validation() {
    if(isset($_SESSION['session_token'])) {
        if($_SERVER['REMOTE_ADDR']===$_SESSION['user_data']['user_ip'] && $_SERVER['HTTP_USER_AGENT']===$_SESSION['user_data']['user_agent']) {
            return true;
        } else {
            session_destroy();
            return false;
        };
    } else {
        return false;
    };
};

function hard_session_validation() {
    if(isset($_SESSION['session_token'])) {
        global $conn;

        $sql = "SELECT ID FROM sessions WHERE CURRENT_TIMESTAMP()<EXPIRES_AT AND DELETED_AT IS NULL AND SESSION_TOKEN=? AND IP_ADDRESS=? AND USER_AGENT=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $_SESSION['session_token'], $_SERVER['REMOTE_ADDR'], $_SERVER['USER_AGENT']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows>0) {
            return true;
        } else {
            return false;
        };
    } else {
        return false;
    };
};

function login_profile() {
    if(soft_session_validation()) {
        echo "<div>";
        echo    "<p>{$_SESSION['user_data']['username']}<i class='fa fa-caret-down'></i></p>";
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

    if(isset($_SESSION['session_token'])) {
        global $conn;
        $session_token = $_SESSION['session_token'];
        
        $sql = "UPDATE sessions SET DELETED_AT = CURRENT_TIMESTAMP WHERE SESSION_TOKEN=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $session_token);
        $stmt->execute();
    };

    session_unset();
    session_destroy();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    };

    header('Location: index.html.php');
};