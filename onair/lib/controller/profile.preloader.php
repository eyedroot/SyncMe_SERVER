<?php

include_once 'app.php';

/**
 * 프로파일 프리로더 컨트롤러
 */
return function ($body) {
    $params = \toObject( json_decode($body, JSON_FORCE_OBJECT) );
    $target_id = property_exists($params, 'load_user_id') ? $params->load_user_id : app()->session('_id');

    if ($target_id) {
        $preloads = userProfile()::get(
            $target_id
        );
    
        endpoint( "PRELOADER", user()::CODE_COMPLETE, (array) $preloads );
    } else {
        endpoint("NOT_LOGGED_IN", app()::CODE_GLOBAL_FAILURE);
    }
};