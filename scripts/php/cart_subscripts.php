<?php
require ("conn.php");
require ("basic_scripts.php");



function show_cart_from_cookie() {
    global $conn;

    $cart_size = count($_SESSION['cart']['games']);

    $placeholders = implode(',', array_fill(0, $cart_size, '?'));
    $types = str_repeat('s', $cart_size);

    $games_in_cart_sql = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games
                        JOIN prices ON prices.ID=games.PRICE_ID
                        WHERE games.ID IN ($placeholders);";

    $games_in_cart_stmt = $conn->prepare($games_in_cart_sql);
    $games_in_cart_stmt->bind_param($types, ...$_SESSION['cart']['games']);
    $games_in_cart_stmt->execute();
    $games_in_cart_result = $games_in_cart_stmt->get_result();

    while($game_in_cart=mysqli_fetch_array($games_in_cart_result)) {
        echo_gamedata_link($game_in_cart);
    };
};



function merge_cookie_cart_with_db_cart() {
    global $conn;

    $games_in_cart_sql = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games 
                        JOIN games_carts ON games_carts.GAME_ID=games.ID
                        JOIN carts ON carts.ID=games_carts.CART_ID
                        JOIN prices ON prices.ID=games.PRICE_ID
                        JOIN users ON users.ID=carts.USER_ID
                        WHERE CURRENT_TIMESTAMP()<carts.EXPIRES_AT
                        AND carts.ID=?;";

    $games_in_cart_stmt = $conn->prepare($games_in_cart_sql);
    $games_in_cart_stmt->bind_param("i",$_SESSION['cart']['id']);
    $games_in_cart_stmt->execute();
    $games_in_cart_result = $games_in_cart_stmt->get_result();

    //to be continued
};



function insert_cookie_cart_to_db() {
    global $conn;

    $cart_size = count($_SESSION['cart']['games']);

    $cart_id_sql = "INSERT INTO carts (USER_ID) VALUES (?);";
    $cart_id_stmt = $conn->prepare($cart_id_sql);
    $cart_id_stmt->bind_param("i",$_SESSION['user_data']['user_id']);
    $cart_id_stmt->execute();

    $_SESSION['cart']['id'] = $conn->insert_id;

    $cart_insert_placeholders = implode(',', array_fill(0, $cart_size, '(?, ?)'));
    $cart_insert_types = str_repeat('ii', $cart_size);
    $cart_insert_params = [];

    foreach ($_SESSION['cart']['games'] as $game_id) {
        $cart_insert_params[] = $_SESSION['cart']['id'];
        $cart_insert_params[] = $game_id;
    };

    $cart_insert_sql = "INSERT INTO games_carts (CART_ID, GAME_ID) VALUES {$cart_insert_placeholders};";
    $cart_insert_stmt = $conn->prepare($cart_insert_sql);
    $cart_insert_stmt->bind_param($cart_insert_types, ...$cart_insert_params);
    $cart_insert_stmt->execute();
};



function synchronize_cart() {
    global $conn;

    $games_in_cart_sql = "SELECT games.ID as GAME_ID FROM games 
                        JOIN games_carts ON games_carts.GAME_ID=games.ID
                        JOIN carts ON carts.ID=games_carts.CART_ID
                        JOIN users ON users.ID=carts.USER_ID
                        WHERE CURRENT_TIMESTAMP()<carts.EXPIRES_AT
                        AND users.ID=?;";
    $games_in_cart_stmt = $conn->prepare($games_in_cart_sql);
    $games_in_cart_stmt->bind_param('i', $_SESSION['user_data']['user_id']);
    $games_in_cart_stmt->execute();
    $games_in_cart_result = $games_in_cart_stmt->get_result();

    if ($games_in_cart_result->num_rows>0) {
        $_SESSION['cart']['games'] = [];
        while ($game_in_cart=mysqli_fetch_array($games_in_cart_result)) {
            array_push($_SESSION['cart']['games'],$game_in_cart['GAME_ID']);
        };
    };

    $cart_id_sql = "SELECT carts.ID as CART_ID FROM carts
                    JOIN users ON users.ID=carts.USER_ID
                    WHERE CURRENT_TIMESTAMP()<carts.EXPIRES_AT
                    AND users.ID=?;";
    $cart_id_stmt = $conn->prepare($cart_id_sql);
    $cart_id_stmt->bind_param('i',$_SESSION['user_data']['user_id']);
    $cart_id_stmt->execute();
    $cart_id_result = $cart_id_stmt->get_result();

    $_SESSION['cart']['id'] = mysqli_fetch_array($cart_id_result)['CART_ID'];
};



function count_cart_value($games_in_cart) {
    global $conn;

    $placeholders = implode(", ",array_fill(0, count($games_in_cart), "?"));
    $types = str_repeat("i", count($games_in_cart));

    $games_prices_sql = "SELECT PRICE FROM games JOIN prices ON prices.ID=games.PRICE_ID WHERE games.ID IN ({$placeholders});";
    $games_prices_stmt = $conn->prepare($games_prices_sql);
    $games_prices_stmt->bind_param($types, ...$games_in_cart);
    $games_prices_stmt->execute();
    $games_prices = $games_prices_stmt->get_result();
    
    $total_cart_price = 0;
    while ($game_price = mysqli_fetch_array($games_prices)) {
        $total_cart_price += $game_price['PRICE'];
    };
    echo $total_cart_price;
};