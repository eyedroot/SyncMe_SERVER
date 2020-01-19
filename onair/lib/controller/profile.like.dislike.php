<?php

include_once 'app.php';

/**
 * 좋아요/싫어요 컨트롤러
 */
return function ($body) {
    $body  = toObject(json_decode($body));
    $point = intval($body->point);
    $id    = trim($body->id);

    // 레디스 중복 키
    $key   = sprintf('likedislike_%s_%s', app()::session('_id'), $id);
    $redis = handleDB('redis');

    if (! $redis->select(7)) {
        endpoint("서버 처리에 실패하였습니다. (1)", app()::CODE_GLOBAL_FAILURE);
    }

    if ($redis->get($key)) {
        endpoint("하루에 한 번 가능합니다.", app()::CODE_GLOBAL_FAILURE);
    }

    if (userProfile()::likeOrDislike($id, $point)) {
        $redis->set($key, 1, 3600 * 24);

        endpoint("해당 유저의 상태가 변화되었습니다.", app()::CODE_GLOBAL_COMPLETE);
    } else {
        endpoint("서버 처리에 실패하였습니다. (2)", app()::CODE_GLOBAL_FAILURE);
    }
};