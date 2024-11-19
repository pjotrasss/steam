function log_out() {
    global $conn;
    $session_token = $_COOKIE['user_session'];
    
    setcookie('user_session', '', time() - 3600, '/');

    $sql = "UPDATE sessions SET DELETED_AT = CURRENT_TIMESTAMP WHERE SESSION_TOKEN=?;";
    $stmt = conn->prepare($sql);
    $stmt->bind_param("s", $session_token);
    $stmt->execute();
}