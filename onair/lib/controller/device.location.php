<?php

include_once 'app.php';

/**
 * 디바이스 위치 수신
 */
return function ($body) {
    $body       = \toObject(json_decode($body));
    $latitude   = (float) $body->latitude;
    $longtitude = (float) $body->longtitude;

    // user 콜렉션의 location 필드를 업데이트 시킨다
    if (user()->updateLastLocation($latitude, $longtitude)) {
        if (handleTagMatch()->updateMatchTable()) {
            endpoint("위치 업데이트가 완료되었습니다.", app()::CODE_GLOBAL_COMPLETE);
        }
    }

    endpoint("위치 업데이트 처리가 실패하였습니다.", app()::CODE_GLOBAL_FAILURE);
};