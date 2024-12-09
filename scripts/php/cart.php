<?php
require ("conn.php");
require ("basic_scripts.php");
require_once ("user_sessions.php");

function show_cart() {

    if (hard_session_validation()) {
        if (isset($_SESSION['cart']['id'])) {
            global $conn;

            $sql1 = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games 
                    JOIN games_carts ON games_carts.GAME_ID=games.ID
                    JOIN carts ON carts.ID=games_carts.CART_ID
                    JOIN prices ON prices.ID=games.PRICE_ID
                    JOIN users ON users.ID=carts.USER_ID
                    WHERE CURRENT_TIMESTAMP()<carts.EXPIRES_AT
                    AND carts.ID=?;";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("i",$_SESSION['cart']['id']);
            $stmt1->execute();
            $result1 = $stmt1->get_result();

            while($row = mysqli_fetch_array($result1)) {
                echo_gamedata_link($row);
            };
        } elseif (isset($_SESSION['cart']['games'])) {
            $cart_size = count($_SESSION['cart']['games']);

            $placeholders = implode(',', array_fill(0, $cart_size, '?'));
            $types = str_repeat('s', $cart_size);

            global $conn;
            $sql2 = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games JOIN prices ON prices.ID=games.PRICE_ID WHERE games.ID IN ($placeholders);";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param($types, ...$_SESSION['cart']['games']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            while($row=mysqli_fetch_array($result2)) {
                echo_gamedata_link($row);
            };

            if ($cart_size>0) {
                $sql3 = "INSERT INTO carts (USER_ID) VALUES (?);";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bind_param("i",$_SESSION['user_data']['user_id']);
                $stmt3->execute();

                $_SESSION['cart']['id'] = $conn->insert_id;

                $placeholders4 = implode(',', array_fill(0, $cart_size, '(?, ?)'));
                $types4 = str_repeat('ii', $cart_size);
                $params4 = [];
                foreach ($_SESSION['cart']['games'] as $game_id) {
                    $params4[] = $_SESSION['cart']['id'];
                    $params4[] = $game_id;
                };

                $sql4 = "INSERT INTO games_carts (CART_ID, GAME_ID) VALUES {$placeholders4};";
                $stmt4 = $conn->prepare($sql4);
                $stmt4->bind_param($types4, ...$params4);
                $stmt4->execute();
            };
        } elseif (isset($_SESSION['user_data']['user_id'])) {
            
        } else {
            echo 'Your cart is empty';
        }

    } else {
        header('Location: ../../login.html.php');
    };
};