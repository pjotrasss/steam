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

        $active_session_sql = "SELECT ID FROM sessions WHERE CURRENT_TIMESTAMP()<EXPIRES_AT AND DELETED_AT IS NULL AND SESSION_TOKEN LIKE ? AND IP_ADDRESS=? AND USER_AGENT=?;";
        $active_session_stmt = $conn->prepare($active_session_sql);
        $active_session_stmt->bind_param("sss", $_SESSION['session_token'], $_SERVER['REMOTE_ADDR'], $_SERVER['USER_AGENT']);
        $active_session_stmt->execute();
        $active_session_result = $active_session_stmt->get_result();

        if ($active_session_result->num_rows>0) {
            return true;
        } else {
            return 20;
        };
    } else {
        return false;
    };
};

function login_profile() {
    if(soft_session_validation()) {
        echo "<div>";
        echo    "<p>{$_SESSION['user_data']['username']}<i class='fa fa-caret-down'></i></p>";
        echo $_SESSION['user_data']['user_id'];
        echo    "<div class='subnav'>";
        echo        "<a class='navitem subnav_item' href='game_library.html.php'>Library</a>";
        echo        "<a class='navitem subnav_item' href='cart.html.php'>Cart</a>";
        echo        "<form class='subnav_item' method='post'>";
        echo            "<input type='submit' value='logout' class='subnav_item' name='logout'/>";
        echo        "</form>";
        echo    "</div>";
        echo "</div>";
    } else {
        echo "<a class='navitem' href='login.html.php'>LOGIN</a>";
    }
};

if(isset($_POST['logout'])) {

    if(isset($_SESSION['session_token'])) {
        global $conn;
        
        $end_session_sql = "UPDATE sessions SET DELETED_AT = CURRENT_TIMESTAMP WHERE SESSION_TOKEN=?;";
        $end_session_stmt = $conn->prepare($end_session_sql);
        $end_session_stmt->bind_param("s", $_SESSION['session_token']);
        $end_session_stmt->execute();
    };

    session_unset();
    session_destroy();

    if (ini_get("session.use_cookies")) {
        $cookie_params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure'], $cookie_params['httponly']);
    };

    header('Location: index.html.php');
};