<?php

function prepare_game_data($row){
    return [
        'logo_url' => htmlspecialchars($row['LOGO_URL']),
        'title' => htmlspecialchars($row['TITLE']),
        'id' => $row['ID'],
        'description' => htmlspecialchars($row['DESCRIPTION'])
    ];
};



function echo_gamedata_link($row){
    $gamedata = prepare_game_data($row);
    echo "<a href='game.html.php?id={$gamedata['id']}' class='gamebox'>";
    echo        "<img src='{$gamedata['logo_url']}' alt='{$gamedata['title']}' />";
    echo        "<div class='gamedata_box'>";
    echo            "<p>{$gamedata['title']}</p>";
    echo            "<p>{$gamedata['description']}</p>";
    echo        "</div>";
    echo "</a>";
};