<?php
require ("login_subscripts.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['signin'])) {
        $hashed_password = select_hashed_password();
        if (!empty($hashed_password)&&password_verify($_POST['password'],$hashed_password)) {
            start_user_session();
            synchronize_cart();
        } else {
            echo "Incorrect username or password";
        };
        exit();
    };
};