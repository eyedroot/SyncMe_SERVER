<?php

include_once 'app.php';

/**
 * 디바이스 위치 수신
 */
return function ($body) {
    $body       = \toObject(json_decode($body));
    $longtitude = (float) $body->longtitude;
    $latitude   = (float) $body->latitude;

    // user 콜렉션의 location 필드를 업데이트 시킨다
    if (user()->updateLastLocation($longtitude, $latitude)) {
        if (handleTagMatch()->updateMatchTable($longtitude, $latitude)) {
            endpoint("위치 및 매칭 테이블 업데이트가 완료되었습니다.", app()::CODE_GLOBAL_COMPLETE);
        }
    }

    endpoint("위치 업데이트 처리가 실패하였습니다.", app()::CODE_GLOBAL_FAILURE);
};