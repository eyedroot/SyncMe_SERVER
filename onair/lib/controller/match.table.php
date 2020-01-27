<?php

include_once 'app.php';

return function ($body) {
    $body = toObject(json_decode($body));
    $user = user()::get()[0];
    $profile = userProfile()::get();
    
    $table = handleTagMatch()->getTable(
        $profile->distance,
        $user->location->coordinates,
        $profile->religion
    );

    foreach ($table as $row) {
        print_r($row);
    }

};