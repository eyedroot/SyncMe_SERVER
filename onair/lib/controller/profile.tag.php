<?php

include_once 'app.php';

/**
 * 태그 컨트롤러
 */
return function ($body) {
    $body = toObject( json_decode($body) );

    if (! sessionValid()) {
        endpoint( "NO_SESSION", app()::CODE_GLOBAL_FAILURE );
    }

    if ($body->method == 'add') {

    }

    switch ($body->method) {
        case 'add':
            $result = handleTag()::put($body->cond, $body->tag);

            if ($result == handleTag()::CODE_ALREADY_TAG_EXISTS) {
                endpoint( "이미 등록된 태그입니다.", handleTag()::CODE_ALREADY_TAG_EXISTS );
            }
            else if ($result == app()::CODE_GLOBAL_FAILURE) {
                endpoint( "태그 등록이 완료되었습니다", app()::CODE_GLOBAL_COMPLETE );
            }
        break;
        case 'delete':
        break;
        default:
            endpoint( "I_DONT_KNOW_THIS_CALL", app()::CODE_GLOBAL_FAILURE );
    }
};