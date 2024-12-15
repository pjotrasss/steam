<?php

function prepare_game_data($game){
    return [
        'logo_url' => htmlspecialchars($game['LOGO_URL']),
        'title' => htmlspecialchars($game['TITLE']),
        'id' => $game['ID'],
        'description' => htmlspecialchars($game['DESCRIPTION']),
        'price' => $game['PRICE']
    ];
};



function echo_gamedata_link($game){
    $gamedata = prepare_game_data($game);
    echo "<a href='game.html.php?id={$gamedata['id']}' class='gamebox'>";
    echo        "<img src='{$gamedata['logo_url']}' alt='{$gamedata['title']}' />";
    echo        "<div class='gamedata_box'>";
    echo            "<p>{$gamedata['title']}</p>";
    echo            "<p>{$gamedata['description']}</p>";
    echo            "<p>PRICE: {$gamedata['price']}USD</p>";
    echo        "</div>";
    echo "</a>";
};