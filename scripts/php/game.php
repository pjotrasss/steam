<?php
require "conn.php";
require "basic_scripts.php";


//finding title of game selected by user
function show_game_title($id) {
    global $conn;
    $sql = "SELECT TITLE from games WHERE games.ID={$id};";
    $result = mysqli_fetch_array($conn->query($sql));
    echo htmlspecialchars($result['TITLE']);
};



function show_game_info($id) {
    global $conn;
    
    $sql = "SELECT * from games WHERE games.ID={$id};";
    $result = mysqli_fetch_array($conn->query($sql));
    $gamedata = prepare_game_data($result);
    
    echo "<h1>{$gamedata['title']}</h1>";
    echo "<img src='{$gamedata['logo_url']}' />";
    echo "<p>{$gamedata['description']}</p>";
};



function show_game_details($id) {
    global $conn;
    
    $sql = "SELECT developers.NAME AS DEVNAME, developers.ID as DEVID, publishers.NAME AS PUBNAME, publishers.ID as PUBID, PRICE, RELEASE_DATE FROM games
            JOIN developers ON developers.ID=games.DEVELOPER_ID
            JOIN publishers ON publishers.ID=games.PUBLISHER_ID
            JOIN prices ON prices.ID=games.PRICE_ID
            WHERE games.ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = mysqli_fetch_array($stmt->get_result());

    
    $game_details = [
        'developer' => htmlspecialchars($result['DEVNAME']),
        'devid' => htmlspecialchars($result['DEVID']),
        'publisher' => htmlspecialchars($result['PUBNAME']),
        'pubid' => htmlspecialchars($result['PUBID']),
        'price' => htmlspecialchars($result['PRICE']),
        'release_date' => htmlspecialchars($result['RELEASE_DATE'])
    ];
    
    echo "<p>Developer: <a href='developer_publisher.html.php?id={$game_details['devid']}'>{$game_details['developer']}</a></p>";
    echo "<p>Publisher: <a href='developer_publisher.html.php?id={$game_details['pubid']}'>{$game_details['publisher']}</a></p>";
    echo "<p>Release date: {$game_details['release_date']}</p>";
    echo "<p>Price: {$game_details['price']} USD</p>";


    $sql2 = "SELECT COUNT(ID) AS REVIEW_COUNT, AVG(POSITIVE_NEGATIVE) AS AVG_REVIEW FROM reviews WHERE GAME_ID=?;";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $result2 = mysqli_fetch_array($stmt2->get_result());

    $reviews_info = [
        'review_count' => htmlspecialchars($result2['REVIEW_COUNT']),
        'avg_review' => htmlspecialchars($result2['AVG_REVIEW'])
    ];
    $reviews_info['avg_review'] = round($reviews_info['avg_review'], 2, PHP_ROUND_HALF_UP)*10;

    echo "<p>Average user score: {$reviews_info['avg_review']}/10</p>";
    echo "<p>Total review count: {$reviews_info['review_count']}</p>";


    $sql3 = "SELECT tags.ID as TAG_ID, NAME FROM tags
            JOIN games_tags ON tags.ID=games_tags.TAG_ID
            WHERE games_tags.GAME_ID=?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param('i', $id);
    $stmt3->execute();
    $result3 = $stmt3->get_result();

    $tags = [];
    while ($row=mysqli_fetch_array($result3)) {
        array_push($tags, "<a href='tag.html.php?id={$row['TAG_ID']}'>{$row['NAME']}</a>");
    };

    echo "<p>";
    echo "Tags: ";
    echo implode(", ", $tags);
    echo "</p>";
};



function show_reviews($id) {
    global $conn;
    $sql = "SELECT EMAIL, REVIEW, REVIEW_DATE, POSITIVE_NEGATIVE FROM users
            JOIN reviews ON reviews.USER_ID=users.ID
            JOIN games ON reviews.GAME_ID=games.ID
            WHERE games.ID=?
            ORDER BY REVIEW DESC;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result2 = $stmt->get_result();

    while($row=mysqli_fetch_array($result2)) {
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