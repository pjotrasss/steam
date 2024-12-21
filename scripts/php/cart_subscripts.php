<?php
require ("conn.php");
require ("basic_scripts.php");



function show_cart_from_cookie() {
    global $conn;

    $games_in_cart_sql = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games
                        JOIN prices ON prices.ID=games.PRICE_ID
                        JOIN games_carts ON games_carts.GAME_ID=games.ID
                        JOIN carts ON carts.ID=games_carts.CART_ID
                        WHERE CART_ID=?
                        ORDER BY games_carts.ADDED_AT DESC";

    $games_in_cart_stmt = $conn->prepare($games_in_cart_sql);
    $games_in_cart_stmt->bind_param("i",$_SESSION['cart']['id']);
    $games_in_cart_stmt->execute();
    $games_in_cart_result = $games_in_cart_stmt->get_result();

    while($game_in_cart=mysqli_fetch_array($games_in_cart_result)) {
        echo "<div class='cart_game_container'>";
            echo_gamedata_link($game_in_cart);
            echo "<form name='remove_from_cart' method='POST'>";
                echo "<input type='hidden' value='{$game_in_cart['ID']}' name='game_id' />";
                echo "<input type='submit' value='REMOVE' name='remove_from_cart' />";
                echo "<img src='images/remove_icon.png' alt='remove_icon' class='trach_icon' />";
            echo "</form>";
        echo "</div>";
    };
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



function create_new_cart() {
    global $conn;

    $cart_id_sql = "INSERT INTO carts (USER_ID) VALUES (?);";
    $cart_id_stmt = $conn->prepare($cart_id_sql);
    $cart_id_stmt->bind_param("i",$_SESSION['user_data']['user_id']);
    $cart_id_stmt->execute();

    $_SESSION['cart']['id'] = $conn->insert_id;
};



function insert_to_db_cart() {
    global $conn;

    $cart_size = count($_SESSION['cart']['games']);

    $cart_insert_placeholders = implode(',', array_fill(0, $cart_size, '(?, ?)'));
    $cart_insert_types = str_repeat('ii', $cart_size);
    $cart_insert_params = [];

    foreach ($_SESSION['cart']['games'] as $game_id) {
        $cart_insert_params[] = $_SESSION['cart']['id'];
        $cart_insert_params[] = $game_id;
    };

    $cart_insert_sql = "INSERT INTO games_carts (CART_ID, GAME_ID) VALUES {$cart_insert_placeholders} ON DUPLICATE KEY UPDATE ID=ID;";
    $cart_insert_stmt = $conn->prepare($cart_insert_sql);
    $cart_insert_stmt->bind_param($cart_insert_types, ...$cart_insert_params);
    $cart_insert_stmt->execute();
};



function remove_from_cart() {
    global $conn;

    $remove_from_cart_sql = "DELETE FROM games_carts WHERE CART_ID=? AND GAME_ID=?;";
    $remove_from_cart_stmt = $conn->prepare($remove_from_cart_sql);
    $remove_from_cart_stmt->bind_param("ii", $_SESSION['cart']['id'], $_POST['game_id']);
    if($remove_from_cart_stmt->execute()) {
        header('Location: ../../cart.html.php');
        synchronize_cart();
    };
};