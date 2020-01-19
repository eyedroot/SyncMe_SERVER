<?php

include_once 'app.php';

/**
 * 프로파일 프리로더 컨트롤러
 */
return function ($body) {
    $params = \toObject( json_decode($body, JSON_FORCE_OBJECT) );
    $targetId = property_exists($params, 'loadUserId') && $params->loadUserId
                    ? $params->loadUserId : app()->session('_id');

    if ($targetId) {
        $preloads = userProfile()::get($targetId);
    
        endpoint("PRELOADER", user()::CODE_COMPLETE, (array) $preloads);
    } else {
        endpoint("PRELOADER_LOAD_FAILURE", app()::CODE_GLOBAL_FAILURE);
    }
};