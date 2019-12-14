<?php
/**
 * 회원 프로필 저장 
 */
include_once 'app.php';

return function ($body) {
    $body = json_decode($body, JSON_UNESCAPED_UNICODE);

    if (! $body['nickname']) {
        endpoint( "닉네임을 입력해주세요", user()::CODE_ERROR );
    }

    if (! $body['age']) {
        endpoint( "나이를 입력해주세요", user()::CODE_ERROR );
    }

    if (userProfile()::updateProfile($body)) {
        endpoint( "SUCCESS", user()::CODE_COMPLETE );
    } else {
        endpoint( "FAILURE", user()::CODE_ERROR );
    }
};