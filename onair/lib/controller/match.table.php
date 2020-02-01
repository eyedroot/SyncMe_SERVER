<?php

include_once 'app.php';

return function ($body) {
    $body = toObject(json_decode($body));
    $user = user()::get()[0];
    $profile = userProfile()::get();

    $tagBoundary = [];

    foreach (handleTag()::$tagKeys as $tag) {
        if (property_exists($profile, $tag)) {
            if (is_array($profile->{$tag})) {
                foreach ($profile->{$tag} as $row) {
                    $tagBoundary[] = $row['$$objectId'];
                }
            }
        }
    }
    
    $matchUserCursor = handleTagMatch()->getTable(
        $tagBoundary,
        $profile->distance,
        $user->location->coordinates,
        $profile->religion
    );

    foreach ($matchUserCursor as $user) {
        var_dump($user);
    }

};