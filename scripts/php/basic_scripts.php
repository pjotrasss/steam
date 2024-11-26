<?php

function prepare_game_data($row){
    return [
        'logo_url' => htmlspecialchars($row['LOGO_URL']),
        'title' => htmlspecialchars($row['TITLE']),
        'id' => $row['ID'],
        'description' => htmlspecialchars($row['DESCRIPTION'])
    ];
};
