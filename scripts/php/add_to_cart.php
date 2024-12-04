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
        echo 'produkt znajduje sie w koszyu';
    }
} else {
    header('Location: ../../login.html');
    exit();
};