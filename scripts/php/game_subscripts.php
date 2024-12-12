<?php
require ("conn.php");
require ("basic_scripts.php");



function show_game_logo_description($game_id) {
    global $conn;
    
    $gamedata_sql = "SELECT * from games WHERE games.ID={$game_id};";
    $gamedata_result = mysqli_fetch_array($conn->query($gamedata_sql));
    $gamedata = prepare_game_data($gamedata_result);
    
    echo "<img src='{$gamedata['logo_url']}' />";
    echo "<p>{$gamedata['description']}</p>";
};



function show_game_details($game_id) {
    global $conn;
    
    $game_details_sql = "SELECT developers.NAME AS DEVNAME, developers.ID as DEVID, publishers.NAME AS PUBNAME, publishers.ID as PUBID, PRICE, RELEASE_DATE FROM games
            JOIN developers ON developers.ID=games.DEVELOPER_ID
            JOIN publishers ON publishers.ID=games.PUBLISHER_ID
            JOIN prices ON prices.ID=games.PRICE_ID
            WHERE games.ID=?";
    $game_details_stmt = $conn->prepare($game_details_sql);
    $game_details_stmt->bind_param('i', $game_id);
    $game_details_stmt->execute();
    $game_details_result = mysqli_fetch_array($game_details_stmt->get_result());

    
    $game_details = [
        'developer' => htmlspecialchars($game_details_result['DEVNAME']),
        'devid' => htmlspecialchars($game_details_result['DEVID']),
        'publisher' => htmlspecialchars($game_details_result['PUBNAME']),
        'pubid' => htmlspecialchars($game_details_result['PUBID']),
        'price' => htmlspecialchars($game_details_result['PRICE']),
        'release_date' => htmlspecialchars($game_details_result['RELEASE_DATE'])
    ];
    
    echo "<p>Developer: <a href='developer_publisher.html.php?id={$game_details['devid']}'>{$game_details['developer']}</a></p>";
    echo "<p>Publisher: <a href='developer_publisher.html.php?id={$game_details['pubid']}'>{$game_details['publisher']}</a></p>";
    echo "<p>Release date: {$game_details['release_date']}</p>";
    echo "<p>Price: {$game_details['price']} USD</p>";
};



function show_game_reviews_info($game_id) {
    global $conn;

    $reviews_info_sql = "SELECT COUNT(ID) AS REVIEW_COUNT, AVG(POSITIVE_NEGATIVE) AS AVG_REVIEW FROM reviews WHERE GAME_ID=?;";
    $reviews_info_stmt = $conn->prepare($reviews_info_sql);
    $reviews_info_stmt->bind_param('i', $game_id);
    $reviews_info_stmt->execute();
    $reviews_info_result = mysqli_fetch_array($reviews_info_stmt->get_result());

    $reviews_info = [
        'review_count' => htmlspecialchars($reviews_info_result['REVIEW_COUNT']),
        'avg_review' => htmlspecialchars($reviews_info_result['AVG_REVIEW'])
    ];
    $reviews_info['avg_review'] = round($reviews_info['avg_review'], 2, PHP_ROUND_HALF_UP)*10;

    echo "<p>Average user score: {$reviews_info['avg_review']}/10</p>";
    echo "<p>Total review count: {$reviews_info['review_count']}</p>";
};



function show_game_tags($game_id) {
    global $conn;

    $tags_info_sql = "SELECT tags.ID as TAG_ID, NAME FROM tags
    JOIN games_tags ON tags.ID=games_tags.TAG_ID
    WHERE games_tags.GAME_ID=?";
    $tags_info_stmt = $conn->prepare($tags_info_sql);
    $tags_info_stmt->bind_param('i', $game_id);
    $tags_info_stmt->execute();
    $tags_info_result = $tags_info_stmt->get_result();

    $tags = [];
    while ($row=mysqli_fetch_array($tags_info_result)) {
        array_push($tags, "<a href='tag.html.php?id={$row['TAG_ID']}'>{$row['NAME']}</a>");
    };

    echo "<p>";
    echo "Tags: ";
    echo implode(", ", $tags);
    echo "</p>";
};



function check_logged_user_owns_game($game_id) {
    global $conn;

    $game_in_library_sql = "SELECT GAME_ID FROM games_users WHERE USER_ID=? AND GAME_ID=?;";
    $game_in_library_stmt = $conn->prepare($game_in_library_sql);
    $game_in_library_stmt->bind_param("ii",$_SESSION['user_data']['user_id'],$game_id);
    $game_in_library_stmt->execute();
    $game_in_library_result = $game_in_library_stmt->get_result();
    if($game_in_library_result->num_rows>0) {
        echo    "<input type='submit' value='You already own this game' name='buygame' style='cursor: not-allowed;' />";
    } else {
        echo "<form method='post' action='scripts/php/add_to_cart.php'>";
        echo    "<input type='hidden' value='{$game_id}' name='game_id' />";
        echo    "<input type='submit' value='Add to cart' name='buygame' />";
        echo "</form>";
    };
};



function show_game_reviews($game_id) {
    global $conn;

    $reviews_data_sql = "SELECT EMAIL, REVIEW, REVIEW_DATE, POSITIVE_NEGATIVE FROM users
            JOIN reviews ON reviews.USER_ID=users.ID
            JOIN games ON reviews.GAME_ID=games.ID
            WHERE games.ID=?
            ORDER BY REVIEW DESC;";
    $reviews_data_stmt = $conn->prepare($reviews_data_sql);
    $reviews_data_stmt->bind_param("i", $game_id);
    $reviews_data_stmt->execute();
    $reviews_data_result = $reviews_data_stmt->get_result();

    while($row=mysqli_fetch_array($reviews_data_result)) {
    $review_data = [
        'email' => htmlspecialchars($row['EMAIL']),
        'review' => htmlspecialchars($row['REVIEW']),
        'positive_negative' => htmlspecialchars($row['POSITIVE_NEGATIVE']),
        'date' => htmlspecialchars($row['REVIEW_DATE'])
    ];

    echo "<div class='review'>";
    echo    "<div class='review_row'>";

    if ($review_data['positive_negative']) {
        echo    "<img src='https://store.fastly.steamstatic.com/public/shared/images/userreviews/icon_thumbsUp_v6.png' />";
    } else {
        echo    "<img src='https://store.fastly.steamstatic.com/public/shared/images/userreviews/icon_thumbsDown_v6.png' />";
    };

    echo        "<h2>{$review_data['email']}</h2>";
    echo    "</div>";
    echo    "<div class='review_row'>";
    echo        "<p>{$review_data['date']}</p>";

    if (!empty($review_data['review'])) {
        echo    "<p>{$review_data['review']}</p>";
    } elseif ($review_data['positive_negative']) {
        echo    "<p>Recommended</p>";
    } else {
        echo    "<p>Not recommended</p>";
    };

    echo    "</div>";
    echo "</div>";
    };
};