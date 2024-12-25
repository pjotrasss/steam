<?php
require ("cart_subscripts.php");
require_once ("user_sessions.php");

function show_cart() {
    if (hard_session_validation()) {

        if (!empty($_SESSION['cart']['id']) && !empty($_SESSION['cart']['games'])) {

            insert_to_db_cart();
            show_cart_from_cookie();
            count_cart_value($_SESSION['cart']['games']);

        } elseif (!empty($_SESSION['cart']['games']) && empty($_SESSION['cart']['id'])) {

            create_new_cart();
            insert_to_db_cart();
            show_cart_from_cookie();
            count_cart_value($_SESSION['cart']['games']);

        } else {
            echo 'Your cart is empty';
        };
    } else {
        header('Location: ../../login.html.php');
    };
};

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['remove_from_cart'])) {
    if (remove_from_cart()) {
        $_SESSION['cart']['games'] = remove_value_from_array($_SESSION['cart']['games'], $_POST['game_id'],);
    };
    if (empty($_SESSION['cart']['games'])) {
        global $conn;

        $delete_cart_sql = "UPDATE carts SET EXPIRES_AT=CURRENT_TIMESTAMP() WHERE ID=?;";
        $delete_cart_stmt = $conn->prepare($delete_cart_sql);
        $delete_cart_stmt->bind_param('i', $_SESSION['cart']['id']);

        if ($delete_cart_stmt->execute()) {
            $_SESSION['cart']['id'] = null;
        };
    };
};