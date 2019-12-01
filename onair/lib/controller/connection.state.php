<?php

include_once 'app.php';

/**
 * 상태 체크
 */
return function ($entityBody) 
{
    $entity = \toObject( json_decode($entityBody, JSON_FORCE_OBJECT) );

    if (! user()::isActive( $entity->token )) {
        endpoint( "인증되지 않았습니다. 새로 로그인해주세요", user()::CODE_ERROR );
    } else {
        // 인증 처리가 되었을 때
        endpoint( "", user()::CODE_COMPLETE );
    }
};