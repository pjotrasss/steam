<?php
require ('user_sessions.php');
session_start();

if (soft_session_validation()) {
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    };
    if(!in_array($_POST['game_id'],$_SESSION['cart'])) {
        array_push($_SESSION['cart'],$_POST['game_id']);
        print_r($_SESSION['cart']);
    } else {
        echo "You've already added this game to your cart";
    }
} else {
    header('Location: ../../login.html');
    exit();
};