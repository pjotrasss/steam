<?php
require ("conn.php");
require ("basic_scripts.php");
require_once ("user_sessions.php");

function show_cart() {
    if (hard_session_validation()) {
        if (!isset($_SESSION['cart']['id'])) {
            if (isset($_SESSION['cart']['games'])) {
                $cart_size = count($_SESSION['cart']['games']);

                $placeholders = implode(',', array_fill(0, $cart_size, '?'));
                $types = str_repeat('s', $cart_size);

                global $conn;
                $sql = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games JOIN prices ON prices.ID=games.PRICE_ID WHERE games.ID IN ($placeholders);";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$_SESSION['cart']['games']);
                $stmt->execute();
                $result = $stmt->get_result();

                while($row=mysqli_fetch_array($result)) {
                    echo_gamedata_link($row);
                };

                if ($cart_size>0) {

                    $sql2 = "INSERT INTO carts (USER_ID) VALUES (?);";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param("i",$_SESSION['user_data']['user_id']);
                    $stmt2->execute();
                    $_SESSION['cart']['id'] = $conn->insert_id;

                    $placeholders3 = implode(',', array_fill(0, $cart_size, '(?, ?)'));
                    $types3 = str_repeat('ii', $cart_size);
                    $params3 = [];
                    foreach ($_SESSION['cart']['games'] as $game_id) {
                        $params3[] = $_SESSION['cart']['id'];
                        $params3[] = $game_id;
                    };

                    $sql3 = "INSERT INTO games_carts (CART_ID, GAME_ID) VALUES {$placeholders3};";
                    $stmt3 = $conn->prepare($sql3);
                    $stmt3->bind_param($types3, ...$params3);
                    $stmt3->execute();
                };
            } else {
                echo 'Your cart is empty';
            };
        } else {
            global $conn;
            $sql4 = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games 
                    JOIN games_carts ON games_carts.GAME_ID=games.ID
                    JOIN carts ON carts.ID=games_carts.CART_ID
                    JOIN prices ON prices.ID=games.PRICE_ID
                    JOIN users ON users.ID=carts.USER_ID
                    WHERE CURRENT_TIMESTAMP()<carts.EXPIRES_AT
                    AND carts.ID=?;";
            $stmt4 = $conn->prepare($sql4);
            $stmt4->bind_param("i",$_SESSION['cart']['id']);
            $stmt4->execute();
            $result4 = $stmt4->get_result();
            
            while($row = mysqli_fetch_array($result4)) {
                echo_gamedata_link($row);
            };
        };
    } else {
        header('Location: ../../login.html.php');
    };
};