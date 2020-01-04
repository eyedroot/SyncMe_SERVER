<?php
/**
 * 로그아웃 컨트롤러
 */
return function ($body) {
    $body = toObject(json_decode($body));

    if (User()::logout($body->token)) {
        \endpoint( "로그아웃 되었습니다.", app()::CODE_GLOBAL_COMPLETE );
    }

    \endpoint( "로그아웃 처리 실패", app()::CODE_GLOBAL_FAILURE );
};