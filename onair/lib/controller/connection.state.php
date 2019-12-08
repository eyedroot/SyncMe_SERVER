<?php

include_once 'app.php';

/**
 * 상태 체크
 */
return function ($entityBody) 
{
    $entity = \toObject( json_decode($entityBody, JSON_FORCE_OBJECT) );

    // token이 없으면
    // 기기를 새로 변경했거나 앱을 삭제 후 새로 설치한 상황
    // TODO 후자라면 핸드폰 인증을 통해서 새로 token을 받아야 함
    // TODO 로그인 액티비티에 `인증 받기` 버튼을 추가해야함함
    // if (! user()::isActive( $entity->token )) {
    //     endpoint( "인증되지 않은 기기입니다. 새로 인증을 해주세요.", user()::CODE_ERROR );
    // } else {
    //     // 인증 처리 OK
    //     $_SESSION['token'] = $entity->token;

    //     endpoint( "", user()::CODE_COMPLETE );
    // }

    if (! $entity->token) {
        endpoint( "단말기 인증을 실패하였습니다. 
        로그인 화면에서 단말기 인증을 진행해주세요.", user()::CODE_ERROR );
    }

    if (! $entity->login_email) {
        $entity->login_email = '';
    }

    if (! $entity->login_password) {
        $entity->login_password = '';
    }

    // login
    user()::login( $entity->token, $entity->login_email, $entity->login_password );
};