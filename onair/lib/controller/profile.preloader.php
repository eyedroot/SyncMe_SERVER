<?php

include_once 'app.php';

/**
 * 프로파일 프리로더 컨트롤러
 */
return function ($body) {
    $preloads = userProfile()::get(
        app()->session('_id')
    );

    endpoint( "PRELOADER", user()::CODE_COMPLETE, (array) $preloads );
};