<?php
require_once ('user_sessions.php');



if (soft_session_validation()) {
    if(!isset($_SESSION['cart']['games'])) {
        $_SESSION['cart']['games'] = [];
    };
    if(!in_array($_POST['game_id'],$_SESSION['cart']['games'])) {
        array_push($_SESSION['cart']['games'],$_POST['game_id']);
    } else {
        echo "You've already added this game to your cart";
    }
} else {
    header('Location: ../../login.html.php');
    exit();
};