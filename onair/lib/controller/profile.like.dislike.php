<?php

include_once 'app.php';

/**
 * 좋아요/싫어요 컨트롤러
 */
return function ($body) {
    $body  = toObject(json_decode($body));
    $point = intval($body->point);
    $id    = trim($body->id);

    if (userProfile()::likeOrDislike($id, $point)) {
        endpoint("해당 유저의 상태가 변화되었습니다.", app()::CODE_GLOBAL_COMPLETE);
    } else {
        endpoint("서버 처리에 실패하였습니다.", app()::CODE_GLOBAL_FAILURE);
    }
};